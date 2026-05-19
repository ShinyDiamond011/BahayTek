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
            'title'            => 'required|string|max:255',
            'description'      => 'nullable|string',
            'session_datetime' => 'required|date|after:now',
            'max_participants' => 'required|integer|min:1',
            'venue'            => 'required|string|max:255',
            'fee'              => 'required|numeric|min:0',
            'status'           => 'required|in:open,coming_soon,ongoing,completed,cancelled',
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
            'title'            => 'required|string|max:255',
            'description'      => 'nullable|string',
            'session_datetime' => 'required|date',
            'max_participants' => 'required|integer|min:1',
            'venue'            => 'required|string|max:255',
            'fee'              => 'required|numeric|min:0',
            'status'           => 'required|in:open,coming_soon,ongoing,completed,cancelled',
        ]);

        $session->update($validated);

        return back()->with('success', 'Workshop updated.');
    }

    public function destroy(TrainingSession $session)
    {
        $session->update(['status' => 'cancelled']);
        return redirect()->route('admin.workshops.index')->with('success', 'Workshop cancelled.');
    }

    public function updateRegistration(Request $request, TrainingRegistration $registration)
    {
        $request->validate(['registration_status' => 'required|in:pending,confirmed,cancelled,attended']);
        $registration->update(['registration_status' => $request->registration_status]);
        return back()->with('success', 'Registration updated.');
    }
}
