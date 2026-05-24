<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\BookingSchedule;
use App\Models\Schedule;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ConsultationController extends Controller
{
    public function index()
    {
        $upcomingSlots = Schedule::where('status', 'available')
            ->where('date', '>', today())
            ->where('date', '<=', today()->addDays(14))
            ->orderBy('date')
            ->orderBy('start_time')
            ->get()
            ->groupBy(fn($s) => $s->date->toDateString());

        return view('consultation.index', compact('upcomingSlots'));
    }

    public function slots(Request $request)
    {
        $request->validate(['date' => 'required|date|after_or_equal:today']);

        $date = Carbon::parse($request->date);

        if ($date->isWeekend() || $date->isToday()) {
            return response()->json(['slots' => []]);
        }

        $slots = Schedule::whereDate('date', $date)
            ->where('status', 'available')
            ->orderBy('start_time')
            ->get()
            ->map(fn($s) => [
                'id'         => $s->id,
                'start_time' => $s->start_time,
                'end_time'   => $s->end_time,
                'type'       => $s->type,
                'type_label' => $s->type_label,
                'time_range' => $s->time_range,
            ]);

        return response()->json(['slots' => $slots]);
    }

    public function book(Request $request)
    {
        $validated = $request->validate([
            'schedule_id'        => 'required|exists:schedules,id',
            'first_name'         => 'required|string|max:100',
            'last_name'          => 'required|string|max:100',
            'email'              => 'required|email|max:255',
            'phone'              => 'nullable|string|max:20',
            'organization'       => 'nullable|string|max:255',
            'no_attendees'       => 'required|integer|min:1|max:50',
            'topic'              => 'required|string|max:500',
            'description'        => 'nullable|string|max:2000',
            'is_research_collab' => 'boolean',
            'collab_institution' => 'nullable|string|max:255',
            'collab_type'        => 'nullable|string|max:100',
        ]);

        $schedule = Schedule::findOrFail($validated['schedule_id']);

        if ($schedule->status !== 'available') {
            return back()->withErrors(['schedule_id' => 'That slot is no longer available. Please choose another.'])->withInput();
        }

        BookingSchedule::create(array_merge($validated, [
            'user_id'            => Auth::id(),
            'is_research_collab' => $request->boolean('is_research_collab'),
        ]));

        $schedule->update(['status' => 'booked']);

        ActivityLog::record(
            'consultation_booked',
            'Consultation booked for ' . $validated['first_name'] . ' ' . $validated['last_name'] . ' on ' . $schedule->date,
            Auth::id(), null,
            ['schedule_id' => $schedule->id, 'topic' => $validated['topic']],
            request()->ip()
        );

        return redirect()->route('consultation.my-bookings')
            ->with('success', 'Your consultation has been booked! We will confirm it within 24 hours.');
    }

    public function myBookings()
    {
        $bookings = BookingSchedule::with('schedule')
            ->where('user_id', Auth::id())
            ->orderByDesc('booked_at')
            ->paginate(10);

        return view('consultation.my-bookings', compact('bookings'));
    }

    public function cancel(BookingSchedule $booking)
    {
        abort_if($booking->user_id !== Auth::id(), 403);
        abort_if(!in_array($booking->status, ['pending', 'confirmed']), 400, 'This booking cannot be cancelled.');

        $booking->update([
            'status'       => 'cancelled',
            'cancelled_at' => now(),
        ]);

        // Free the schedule slot again
        $booking->schedule?->update(['status' => 'available']);

        return back()->with('success', 'Booking cancelled successfully.');
    }
}
