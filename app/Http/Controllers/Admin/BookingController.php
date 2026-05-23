<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BookingSchedule;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        $query = BookingSchedule::with(['user', 'schedule'])
            ->orderByDesc('booked_at');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('type')) {
            $query->whereHas('schedule', fn($q) => $q->where('type', $request->type));
        }

        if ($request->filled('search')) {
            $search = '%' . $request->search . '%';
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', $search)
                  ->orWhere('last_name', 'like', $search)
                  ->orWhere('email', 'like', $search)
                  ->orWhere('topic', 'like', $search);
            });
        }

        $bookings = $query->paginate(20)->withQueryString();

        $counts = [
            'all'       => BookingSchedule::count(),
            'pending'   => BookingSchedule::where('status', 'pending')->count(),
            'confirmed' => BookingSchedule::where('status', 'confirmed')->count(),
            'declined'  => BookingSchedule::where('status', 'declined')->count(),
            'cancelled' => BookingSchedule::where('status', 'cancelled')->count(),
            'completed' => BookingSchedule::where('status', 'completed')->count(),
        ];

        return view('admin.bookings.index', compact('bookings', 'counts'));
    }

    public function show(BookingSchedule $booking)
    {
        $booking->load(['user', 'schedule']);
        return view('admin.bookings.show', compact('booking'));
    }

    public function updateStatus(Request $request, BookingSchedule $booking)
    {
        $request->validate([
            'status'      => 'required|in:pending,confirmed,declined,completed',
            'admin_notes' => 'nullable|string|max:1000',
        ]);

        $booking->update([
            'status'      => $request->status,
            'admin_notes' => $request->admin_notes,
        ]);

        // If declined or completed, free the schedule slot
        if (in_array($request->status, ['declined'])) {
            $booking->schedule?->update(['status' => 'available']);
        }

        return back()->with('success', 'Booking status updated.');
    }

    // ── Schedule Slot Management ──────────────────────────────────────────────

    public function schedules(Request $request)
    {
        $query = Schedule::with('creator')->orderBy('date')->orderBy('start_time');

        if ($request->filled('date')) {
            $query->whereDate('date', $request->date);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $schedules = $query->paginate(25)->withQueryString();

        return view('admin.bookings.schedules', compact('schedules'));
    }

    public function createSlot(Request $request)
    {
        $validated = $request->validate([
            'date'       => 'required|date|after_or_equal:today',
            'start_time' => 'required|date_format:H:i',
            'end_time'   => 'required|date_format:H:i|after:start_time',
            'type'       => 'required|in:research_consultancy,product_development,general_consultancy,any',
            'slots'      => 'required|integer|min:1|max:10',
        ]);

        $count = (int) $validated['slots'];
        unset($validated['slots']);

        // Count how many identical slots already exist to avoid unbounded duplicates
        $existing = Schedule::where('date', $validated['date'])
            ->where('start_time', $validated['start_time'])
            ->where('end_time', $validated['end_time'])
            ->where('type', $validated['type'])
            ->count();

        // Only create slots up to the requested count minus any that already exist
        $toCreate = max(0, $count - $existing);

        if ($toCreate === 0) {
            return back()->with('error', 'Identical slot(s) for this date and time already exist. No new slots were created.');
        }

        for ($i = 0; $i < $toCreate; $i++) {
            Schedule::create(array_merge($validated, [
                'status'     => 'available',
                'created_by' => Auth::guard('staff')->id(),
            ]));
        }

        return back()->with('success', $toCreate . ' slot(s) created successfully.');
    }

    public function deleteSlot(Schedule $schedule)
    {
        abort_if($schedule->status === 'booked', 400, 'Cannot delete a booked slot.');
        $schedule->delete();
        return back()->with('success', 'Schedule slot deleted.');
    }
}
