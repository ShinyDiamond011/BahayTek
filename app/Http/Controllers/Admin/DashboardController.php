<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BookingSchedule;
use App\Models\Order;
use App\Models\Product;
use App\Models\TrainingRegistration;
use App\Models\TrainingSession;
use App\Models\User;
class DashboardController extends Controller
{
    public function index()
    {
        $now   = now();
        $today = $now->toDateString();
        $month = $now->month;
        $year  = $now->year;

        // ── Counts ────────────────────────────────────────────────────────────
        $stats = [
            'total_users'         => User::count(),
            'new_users_month'     => User::whereMonth('created_at', $month)->whereYear('created_at', $year)->count(),
            'total_orders'        => Order::count(),
            'orders_today'        => Order::whereDate('ordered_at', $today)->count(),
            'revenue_month'       => Order::whereMonth('ordered_at', $month)->whereYear('ordered_at', $year)
                                         ->where('payment_status', 'paid')->sum('total_amount'),
            'revenue_total'       => Order::where('payment_status', 'paid')->sum('total_amount'),
            'pending_orders'      => Order::where('status', 'pending')->count(),
            'pending_bookings'    => BookingSchedule::where('status', 'pending')->count(),
            'active_products'     => Product::where('is_active', true)->count(),
            'low_stock'           => Product::where('stock_level', 'low_stock')->count(),
            'out_of_stock'        => Product::where('stock_level', 'out_of_stock')->count(),
            'upcoming_workshops'  => TrainingSession::whereIn('status', ['open', 'coming_soon', 'ongoing'])->count(),
            'pending_enrollments' => TrainingRegistration::where('registration_status', 'pending')->count(),
        ];

        // ── Recent Orders ─────────────────────────────────────────────────────
        $recentOrders = Order::with('user')
            ->orderByDesc('ordered_at')
            ->limit(8)
            ->get();

        // ── Recent Bookings ───────────────────────────────────────────────────
        $recentBookings = BookingSchedule::with('schedule')
            ->orderByDesc('booked_at')
            ->limit(6)
            ->get();

        // ── Monthly Revenue (last 6 months) ───────────────────────────────────
        $revenueChart = Order::where('payment_status', 'paid')
            ->where('ordered_at', '>=', now()->subMonths(5)->startOfMonth())
            ->selectRaw("DATE_FORMAT(ordered_at, '%Y-%m') as month, SUM(total_amount) as total")
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // ── Order Status breakdown ────────────────────────────────────────────
        $orderStatusBreakdown = Order::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status');

        return view('admin.dashboard', compact(
            'stats', 'recentOrders', 'recentBookings',
            'revenueChart', 'orderStatusBreakdown'
        ));
    }
}
