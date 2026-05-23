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
        $fmt    = $request->get('format', 'csv');

        // PDF: render a printable HTML page
        if ($fmt === 'pdf') {
            return $this->exportPdf($type, $period, $from);
        }

        $filename = "bahaytek-{$type}-report-" . now()->format('Y-m-d') . '.csv';

        $headers = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $generatedAt = now()->format('Y-m-d H:i:s');
        $periodLabel = "Last {$period} days (from {$from->format('Y-m-d')} to " . now()->format('Y-m-d') . ")";

        $callback = match($type) {
            'orders' => function() use ($from, $generatedAt, $periodLabel) {
                $out = fopen('php://output', 'w');
                // Title block
                fputcsv($out, ['BAHAYTEK — Orders Report']);
                fputcsv($out, ['Generated:', $generatedAt]);
                fputcsv($out, ['Period:', $periodLabel]);
                fputcsv($out, []);
                // Column headers
                fputcsv($out, ['Order ID','Customer','Email','Order Status','Payment Status','Payment Method','Total (PHP)','Order Date']);
                Order::with('user')->where('ordered_at', '>=', $from)->orderBy('ordered_at')->chunk(200, function($orders) use ($out) {
                    foreach ($orders as $o) {
                        fputcsv($out, [
                            '#' . str_pad($o->id, 5, '0', STR_PAD_LEFT),
                            trim(($o->user?->first_name ?? '') . ' ' . ($o->user?->last_name ?? '')),
                            $o->user?->email ?? '—',
                            ucfirst($o->status),
                            ucfirst($o->payment_status),
                            strtoupper($o->payment_method ?? '—'),
                            number_format($o->total_amount, 2),
                            $o->ordered_at->format('Y-m-d H:i'),
                        ]);
                    }
                });
                fclose($out);
            },
            'products' => function() use ($generatedAt) {
                $out = fopen('php://output', 'w');
                fputcsv($out, ['BAHAYTEK — Products Report']);
                fputcsv($out, ['Generated:', $generatedAt]);
                fputcsv($out, []);
                fputcsv($out, ['Product ID','Product Name','Category','Price (PHP)','Stock Qty','Stock Level','Active']);
                Product::orderBy('prod_name')->chunk(200, function($products) use ($out) {
                    foreach ($products as $p) {
                        fputcsv($out, [
                            $p->id,
                            $p->prod_name,
                            $p->category,
                            number_format($p->price, 2),
                            $p->stock_qty,
                            ucwords(str_replace('_', ' ', $p->stock_level)),
                            $p->is_active ? 'Yes' : 'No',
                        ]);
                    }
                });
                fclose($out);
            },
            'users' => function() use ($from, $generatedAt, $periodLabel) {
                $out = fopen('php://output', 'w');
                fputcsv($out, ['BAHAYTEK — Users Report']);
                fputcsv($out, ['Generated:', $generatedAt]);
                fputcsv($out, ['Period:', $periodLabel]);
                fputcsv($out, []);
                fputcsv($out, ['User ID','First Name','Last Name','Email','Phone','Registration Date']);
                User::where('created_at', '>=', $from)->orderBy('created_at')->chunk(200, function($users) use ($out) {
                    foreach ($users as $u) {
                        fputcsv($out, [
                            $u->id,
                            $u->first_name,
                            $u->last_name,
                            $u->email,
                            $u->phone ?? '—',
                            $u->created_at->format('Y-m-d'),
                        ]);
                    }
                });
                fclose($out);
            },
            'workshops' => function() use ($from, $generatedAt, $periodLabel) {
                $out = fopen('php://output', 'w');
                fputcsv($out, ['BAHAYTEK — Workshop Enrollments Report']);
                fputcsv($out, ['Generated:', $generatedAt]);
                fputcsv($out, ['Period:', $periodLabel]);
                fputcsv($out, []);
                fputcsv($out, ['Registration ID','Workshop Title','Session Date','Venue','Fee (PHP)','Participant Name','Email','Status','Registered At']);
                TrainingRegistration::with(['user','trainingSession'])
                    ->where('registered_at', '>=', $from)
                    ->orderBy('registered_at')
                    ->chunk(200, function($regs) use ($out) {
                        foreach ($regs as $r) {
                            fputcsv($out, [
                                $r->id,
                                $r->trainingSession?->title ?? '—',
                                $r->trainingSession?->session_datetime?->format('Y-m-d H:i') ?? '—',
                                $r->trainingSession?->venue ?? '—',
                                number_format((float)($r->trainingSession?->fee ?? 0), 2),
                                trim(($r->user?->first_name ?? '') . ' ' . ($r->user?->last_name ?? '')),
                                $r->user?->email ?? '—',
                                ucfirst($r->registration_status),
                                $r->registered_at?->format('Y-m-d H:i') ?? '—',
                            ]);
                        }
                    });
                fclose($out);
            },
            default => function() { echo 'No data'; },
        };

        return response()->stream($callback, 200, $headers);
    }

    private function exportPdf(string $type, string $period, $from)
    {
        $generatedAt  = now()->format('F j, Y g:i A');
        $periodLabel  = "Last {$period} days";

        $rows    = collect();
        $columns = [];
        $title   = 'Report';

        if ($type === 'orders') {
            $title   = 'Orders Report';
            $columns = ['Order ID','Customer','Email','Order Status','Payment','Total (PHP)','Date'];
            $rows    = Order::with('user')->where('ordered_at', '>=', $from)->orderBy('ordered_at')->get()->map(fn($o) => [
                '#' . str_pad($o->id, 5, '0', STR_PAD_LEFT),
                trim(($o->user?->first_name ?? '') . ' ' . ($o->user?->last_name ?? '')),
                $o->user?->email ?? '—',
                ucfirst($o->status),
                ucfirst($o->payment_status),
                '₱' . number_format($o->total_amount, 2),
                $o->ordered_at->format('M j, Y'),
            ]);
        } elseif ($type === 'products') {
            $title   = 'Products Report';
            $columns = ['ID','Product Name','Category','Price (PHP)','Stock Qty','Stock Level','Active'];
            $rows    = Product::orderBy('prod_name')->get()->map(fn($p) => [
                $p->id, $p->prod_name, $p->category,
                '₱' . number_format($p->price, 2),
                $p->stock_qty,
                ucwords(str_replace('_', ' ', $p->stock_level)),
                $p->is_active ? 'Yes' : 'No',
            ]);
        } elseif ($type === 'users') {
            $title   = 'Users Report';
            $columns = ['ID','First Name','Last Name','Email','Phone','Registered'];
            $rows    = User::where('created_at', '>=', $from)->orderBy('created_at')->get()->map(fn($u) => [
                $u->id, $u->first_name, $u->last_name, $u->email, $u->phone ?? '—',
                $u->created_at->format('M j, Y'),
            ]);
        } elseif ($type === 'workshops') {
            $title   = 'Workshop Enrollments Report';
            $columns = ['Workshop','Session Date','Participant','Email','Status','Registered At'];
            $rows    = TrainingRegistration::with(['user','trainingSession'])
                ->where('registered_at', '>=', $from)->orderBy('registered_at')->get()->map(fn($r) => [
                    $r->trainingSession?->title ?? '—',
                    $r->trainingSession?->session_datetime?->format('M j, Y g:i A') ?? '—',
                    trim(($r->user?->first_name ?? '') . ' ' . ($r->user?->last_name ?? '')),
                    $r->user?->email ?? '—',
                    ucfirst($r->registration_status),
                    $r->registered_at?->format('M j, Y') ?? '—',
                ]);
        }

        return view('admin.reports.pdf', compact('title','periodLabel','generatedAt','columns','rows'));
    }
}
