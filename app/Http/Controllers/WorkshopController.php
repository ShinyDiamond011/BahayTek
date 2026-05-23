<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\TrainingRegistration;
use App\Models\TrainingSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WorkshopController extends Controller
{
    public function index(Request $request)
    {
        $showHistory = $request->boolean('history');

        $baseQuery = TrainingSession::withCount([
            'registrations',
            'registrations as confirmed_registrations_count' => fn($q) => $q->where('registration_status', 'confirmed'),
        ]);

        if ($request->filled('search')) {
            $s = '%' . $request->search . '%';
            $baseQuery->where(fn($q) => $q->where('title', 'like', $s)->orWhere('description', 'like', $s)->orWhere('venue', 'like', $s));
        }

        if ($showHistory) {
            // History: session date has passed OR status is completed/cancelled
            $baseQuery->where(function ($q) {
                $q->where('session_datetime', '<', now())
                  ->orWhereIn('status', ['completed', 'cancelled']);
            })->orderByDesc('session_datetime');
        } elseif ($request->filled('status')) {
            $baseQuery->where('status', $request->status)
                      ->where('session_datetime', '>=', now())
                      ->whereNotIn('status', ['completed', 'cancelled'])
                      ->orderBy('session_datetime');
        } else {
            // Active: future date AND not completed/cancelled
            $baseQuery->where('session_datetime', '>=', now())
                      ->whereNotIn('status', ['completed', 'cancelled'])
                      ->orderBy('session_datetime');
        }

        $sessions = $baseQuery->paginate(9)->withQueryString();

        $userEnrollments = [];
        if (Auth::check()) {
            $userEnrollments = TrainingRegistration::where('user_id', Auth::id())
                ->get(['id', 'training_session_id', 'registration_status'])
                ->keyBy('training_session_id')
                ->map(fn($r) => ['id' => $r->id, 'status' => $r->registration_status])
                ->all();
        }

        return view('workshops.index', compact('sessions', 'userEnrollments', 'showHistory'));
    }

    public function enroll(TrainingSession $session)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please log in to enroll.');
        }

        if (!$session->isRegistrationOpen()) {
            if ($session->registration_deadline && now()->gt($session->registration_deadline)) {
                return back()->with('error', 'The registration period for this workshop has closed.');
            }
            return back()->with('error', 'This session is not open for enrollment.');
        }

        $existing = TrainingRegistration::where('user_id', Auth::id())
            ->where('training_session_id', $session->id)
            ->first();

        if ($existing) {
            return back()->with('error', 'You are already enrolled in this session.');
        }

        // Issue #8: Check for schedule conflict (same date/time as another confirmed enrollment)
        $conflict = TrainingRegistration::where('user_id', Auth::id())
            ->whereIn('registration_status', ['pending', 'confirmed'])
            ->whereHas('trainingSession', function ($q) use ($session) {
                $q->where('id', '!=', $session->id)
                  ->where('session_datetime', $session->session_datetime);
            })->first();

        if ($conflict) {
            return back()->with('error', 'You already have a workshop enrollment at the same date and time (' . $session->session_datetime->format('F j, Y g:i A') . '). Please cancel the other enrollment first.');
        }

        $confirmedCount = $session->registrations()->where('registration_status', 'confirmed')->count();
        if ($session->max_participants > 0 && $confirmedCount >= $session->max_participants) {
            return back()->with('error', 'This session is already full.');
        }

        TrainingRegistration::create([
            'user_id'             => Auth::id(),
            'training_session_id' => $session->id,
            'registration_status' => 'pending',
            'registered_at'       => now(),
        ]);

        ActivityLog::record(
            'workshop_enrollment',
            'User enrolled in workshop: ' . $session->title,
            Auth::id(), null,
            ['session_id' => $session->id],
            request()->ip()
        );

        return back()->with('success', 'Enrollment request submitted! You will be notified once confirmed.');
    }

    // Issue #7: Users can cancel their own enrollment
    public function cancel(TrainingRegistration $registration)
    {
        if ($registration->user_id !== Auth::id()) {
            abort(403);
        }

        if ($registration->registration_status === 'attended') {
            return back()->with('error', 'You cannot cancel an enrollment that has already been marked as attended.');
        }

        $registration->update(['registration_status' => 'cancelled']);

        ActivityLog::record(
            'workshop_cancellation',
            'User cancelled enrollment for workshop: ' . ($registration->trainingSession?->title ?? 'N/A'),
            Auth::id(), null,
            ['registration_id' => $registration->id],
            request()->ip()
        );

        return back()->with('success', 'Your enrollment has been cancelled.');
    }

    public function myEnrollments()
    {
        $registrations = TrainingRegistration::where('user_id', Auth::id())
            ->with('trainingSession')
            ->orderByDesc('registered_at')
            ->paginate(10);

        return view('workshops.my-enrollments', compact('registrations'));
    }
}
