<?php

namespace App\Http\Controllers;

use App\Models\TrainingRegistration;
use App\Models\TrainingSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WorkshopController extends Controller
{
    public function index(Request $request)
    {
        $query = TrainingSession::withCount([
            'registrations',
            'registrations as confirmed_registrations_count' => fn($q) => $q->where('registration_status', 'confirmed'),
        ])->orderBy('session_datetime');

        if ($request->filled('search')) {
            $s = '%' . $request->search . '%';
            $query->where(fn($q) => $q->where('title', 'like', $s)->orWhere('description', 'like', $s)->orWhere('venue', 'like', $s));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        } else {
            $query->whereIn('status', ['open', 'coming_soon', 'ongoing']);
        }

        $sessions = $query->paginate(9)->withQueryString();

        $userEnrollments = [];
        if (Auth::check()) {
            $userEnrollments = TrainingRegistration::where('user_id', Auth::id())
                ->pluck('registration_status', 'training_session_id')
                ->all();
        }

        return view('workshops.index', compact('sessions', 'userEnrollments'));
    }

    public function enroll(TrainingSession $session)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please log in to enroll.');
        }

        if (!in_array($session->status, ['open', 'ongoing', 'coming_soon'])) {
            return back()->with('error', 'This session is not open for enrollment.');
        }

        $existing = TrainingRegistration::where('user_id', Auth::id())
            ->where('training_session_id', $session->id)
            ->first();

        if ($existing) {
            return back()->with('error', 'You are already enrolled in this session.');
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

        return back()->with('success', 'Enrollment request submitted! You will be notified once confirmed.');
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
