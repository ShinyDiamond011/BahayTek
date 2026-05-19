<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BookingSchedule;
use App\Models\Inventory;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\TrainingRegistration;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $period = $request->get('period', '30');
        $from   = now()->subDays((int) $period)->startOfDay();

        // ── Sales ─────────────────────────────────────────────────────────────
        $salesSummary = [
            'total_orders'   => Order::where('ordered_at', '>=', $from)->count(),
            'paid_orders'    => Order::where('ordered_at', '>=', $from)->where('payment_status', 'paid')->count(),
            'total_revenue'  => Order::where('ordered_at', '>=', $from)->where('payment_status', 'paid')->sum('total_amount'),
            'avg_order'      => Order::where('ordered_at', '>=', $from)->where('payment_status', 'paid')->avg('total_amount') ?? 0,
        ];

        // ── Revenue by day (last N days) ──────────────────────────────────────
        $revenueByDay = Order::where('payment_status', 'paid')
            ->where('ordered_at', '>=', $from)
            ->selectRaw("DATE(ordered_at) as day, SUM(total_amount) as total, COUNT(*) as orders")
            ->groupBy('day')
            ->orderBy('day')
            ->get();

        // ── Top Products ──────────────────────────────────────────────────────
        $topProducts = OrderItem::join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('product_variants', 'order_items.variant_id', '=', 'product_variants.id')
            ->join('products', 'product_variants.product_id', '=', 'products.id')
            ->where('orders.ordered_at', '>=', $from)
            ->selectRaw('products.id, products.prod_name, products.category, SUM(order_items.quantity) as units_sold, SUM(order_items.quantity * order_items.unit_price) as revenue')
            ->groupBy('products.id', 'products.prod_name', 'products.category')
            ->orderByDesc('units_sold')
            ->limit(10)
            ->get();

        // ── Orders by status ──────────────────────────────────────────────────
        $ordersByStatus = Order::where('ordered_at', '>=', $from)
            ->selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status');

        // ── Bookings summary ──────────────────────────────────────────────────
        $bookingsSummary = BookingSchedule::where('booked_at', '>=', $from)
            ->selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status');

        // ── Workshop enrollments ──────────────────────────────────────────────
        $workshopSummary = TrainingRegistration::where('registered_at', '>=', $from)
            ->selectRaw('registration_status, COUNT(*) as count')
            ->groupBy('registration_status')
            ->pluck('count', 'registration_status');

        // ── New users ─────────────────────────────────────────────────────────
        $newUsers = User::where('created_at', '>=', $from)->count();

        // ── Recent inventory changes ──────────────────────────────────────────
        $inventoryLog = Inventory::with(['product', 'admin'])
            ->where('recorded_at', '>=', $from)
            ->orderByDesc('recorded_at')
            ->limit(15)
            ->get();

        return view('admin.reports.index', compact(
            'period', 'salesSummary', 'revenueByDay', 'topProducts',
            'ordersByStatus', 'bookingsSummary', 'workshopSummary',
            'newUsers', 'inventoryLog'
        ));
    }

    public function export(Request $request)
    {
        $type   = $request->get('type', 'orders');
        $period = $request->get('period', '30');
        $from   = now()->subDays((int) $period)->startOfDay();

        $filename = "{$type}-report-" . now()->format('Y-m-d') . '.csv';

        $headers = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = match($type) {
            'orders' => function() use ($from) {
                $out = fopen('php://output', 'w');
                fputcsv($out, ['Order ID','Customer','Email','Status','Payment','Method','Total','Date']);
                Order::with('user')->where('ordered_at', '>=', $from)->orderBy('ordered_at')->chunk(200, function($orders) use ($out) {
                    foreach ($orders as $o) {
                        fputcsv($out, [
                            str_pad($o->id, 5, '0', STR_PAD_LEFT),
                            ($o->user?->first_name . ' ' . $o->user?->last_name),
                            $o->user?->email,
                            $o->status,
                            $o->payment_status,
                            $o->payment_method,
                            number_format($o->total_amount, 2),
                            $o->ordered_at->format('Y-m-d H:i'),
                        ]);
                    }
                });
                fclose($out);
            },
            'products' => function() {
                $out = fopen('php://output', 'w');
                fputcsv($out, ['ID','Name','Category','Price','Stock Qty','Stock Level','Active']);
                Product::orderBy('prod_name')->chunk(200, function($products) use ($out) {
                    foreach ($products as $p) {
                        fputcsv($out, [
                            $p->id, $p->prod_name, $p->category,
                            number_format($p->price, 2),
                            $p->stock_qty, $p->stock_level,
                            $p->is_active ? 'Yes' : 'No',
                        ]);
                    }
                });
                fclose($out);
            },
            'users' => function() use ($from) {
                $out = fopen('php://output', 'w');
                fputcsv($out, ['ID','First Name','Last Name','Email','Phone','Registered']);
                User::where('created_at', '>=', $from)->orderBy('created_at')->chunk(200, function($users) use ($out) {
                    foreach ($users as $u) {
                        fputcsv($out, [
                            $u->id, $u->first_name, $u->last_name,
                            $u->email, $u->phone,
                            $u->created_at->format('Y-m-d'),
                        ]);
                    }
                });
                fclose($out);
            },
            default => function() { echo "No data"; },
        };

        return response()->stream($callback, 200, $headers);
    }
}
