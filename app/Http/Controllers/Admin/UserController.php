<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::withCount(['orders', 'bookingSchedules'])->orderByDesc('created_at');

        if ($request->filled('search')) {
            $s = '%' . $request->search . '%';
            $query->where(fn($q) =>
                $q->where('first_name', 'like', $s)
                  ->orWhere('last_name', 'like', $s)
                  ->orWhere('email', 'like', $s)
            );
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $users = $query->paginate(20)->withQueryString();

        return view('admin.users.index', compact('users'));
    }

    public function show(User $user)
    {
        $user->load([
            'orders' => fn($q) => $q->orderByDesc('ordered_at')->limit(10),
            'bookingSchedules' => fn($q) => $q->with('schedule')->orderByDesc('booked_at')->limit(5),
        ]);

        return view('admin.users.show', compact('user'));
    }

    public function updateStatus(Request $request, User $user)
    {
        $request->validate(['status' => 'required|in:active,inactive,suspended']);
        $user->update(['status' => $request->status]);
        return back()->with('success', "User status updated to {$request->status}.");
    }
}
