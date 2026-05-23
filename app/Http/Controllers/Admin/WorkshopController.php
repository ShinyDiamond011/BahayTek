<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
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
            'registrations as confirmed_count' => fn($q) => $q->where('registration_status', 'confirmed'),
        ])->orderByDesc('session_datetime');

        if ($request->filled('search')) {
            $s = '%' . $request->search . '%';
            $query->where(fn($q) => $q->where('title', 'like', $s)->orWhere('venue', 'like', $s));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $sessions = $query->paginate(15)->withQueryString();

        return view('admin.workshops.index', compact('sessions'));
    }

    public function create()
    {
        return view('admin.workshops.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'                 => 'required|string|max:255',
            'description'           => 'nullable|string',
            'session_datetime'      => 'required|date|after:now',
            'registration_deadline' => 'nullable|date|before:session_datetime',
            'max_participants'      => 'required|integer|min:1',
            'venue'                 => 'required|string|max:255',
            'fee'                   => 'required|numeric|min:0',
            'status'                => 'required|in:open,coming_soon,ongoing,completed,cancelled',
        ]);

        $validated['created_by'] = Auth::guard('staff')->id();

        TrainingSession::create($validated);

        return redirect()->route('admin.workshops.index')->with('success', 'Workshop created successfully.');
    }

    public function show(TrainingSession $session)
    {
        $session->load('creator');
        $registrations = TrainingRegistration::where('training_session_id', $session->id)
            ->with('user')
            ->orderByDesc('registered_at')
            ->paginate(20);

        return view('admin.workshops.show', compact('session', 'registrations'));
    }

    public function update(Request $request, TrainingSession $session)
    {
        $validated = $request->validate([
            'title'                 => 'required|string|max:255',
            'description'           => 'nullable|string',
            'session_datetime'      => 'required|date',
            'registration_deadline' => 'nullable|date|before:session_datetime',
            'max_participants'      => 'required|integer|min:1',
            'venue'                 => 'required|string|max:255',
            'fee'                   => 'required|numeric|min:0',
            'status'                => 'required|in:open,coming_soon,ongoing,completed,cancelled',
        ]);

        $session->update($validated);

        return back()->with('success', 'Workshop updated.');
    }

    public function destroy(TrainingSession $session)
    {
        $session->update(['status' => 'cancelled']);
        return redirect()->route('admin.workshops.index')->with('success', 'Workshop cancelled.');
    }

    public function attendance(Request $request)
    {
        // Sessions that are ongoing or completed — these are the ones that need attendance tracking
        $sessionsQuery = TrainingSession::whereIn('status', ['ongoing', 'completed'])
            ->orderByDesc('session_datetime');

        if ($request->filled('session_id')) {
            $sessionsQuery->where('id', $request->session_id);
        }

        $sessions      = TrainingSession::whereIn('status', ['ongoing', 'completed'])->orderByDesc('session_datetime')->get();
        $selectedSession = $request->filled('session_id')
            ? TrainingSession::find($request->session_id)
            : $sessions->first();

        $registrations = collect();
        if ($selectedSession) {
            $registrations = TrainingRegistration::where('training_session_id', $selectedSession->id)
                ->whereIn('registration_status', ['confirmed', 'attended', 'cancelled'])
                ->with('user')
                ->orderBy('registered_at')
                ->get();
        }

        return view('admin.workshops.attendance', compact('sessions', 'selectedSession', 'registrations'));
    }

    public function markAttendance(Request $request, TrainingRegistration $registration)
    {
        $request->validate(['present' => 'required|in:1,0']);

        $registration->update([
            'registration_status' => $request->present === '1' ? 'attended' : 'cancelled',
        ]);

        return back()->with('success', 'Attendance updated.');
    }

    public function updateRegistration(Request $request, TrainingRegistration $registration)
    {
        $request->validate(['registration_status' => 'required|in:pending,confirmed,cancelled,attended']);

        // Once confirmed, status can only move forward (confirmed → attended/cancelled), not back to pending
        if ($registration->registration_status === 'confirmed' && $request->registration_status === 'pending') {
            return back()->with('error', 'A confirmed enrollment cannot be reverted to pending.');
        }

        $registration->update(['registration_status' => $request->registration_status]);
        return back()->with('success', 'Registration updated.');
    }
}
