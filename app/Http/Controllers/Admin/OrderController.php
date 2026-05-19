<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with(['user', 'items'])->orderByDesc('ordered_at');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('payment')) {
            $query->where('payment_status', $request->payment);
        }

        if ($request->filled('search')) {
            $s = '%' . $request->search . '%';
            $query->whereHas('user', fn($u) =>
                $u->where('first_name', 'like', $s)
                  ->orWhere('last_name', 'like', $s)
                  ->orWhere('email', 'like', $s)
            );
        }

        $orders = $query->paginate(20)->withQueryString();

        $counts = [
            'all'        => Order::count(),
            'pending'    => Order::where('status', 'pending')->count(),
            'confirmed'  => Order::where('status', 'confirmed')->count(),
            'processing' => Order::where('status', 'processing')->count(),
            'shipped'    => Order::where('status', 'shipped')->count(),
            'delivered'  => Order::where('status', 'delivered')->count(),
            'cancelled'  => Order::where('status', 'cancelled')->count(),
        ];

        return view('admin.orders.index', compact('orders', 'counts'));
    }

    public function show(Order $order)
    {
        $order->load(['user', 'items.variant.product']);
        $address = json_decode($order->shipping_address ?? '{}', true) ?? [];
        return view('admin.orders.show', compact('order', 'address'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status'         => 'required|in:pending,confirmed,processing,shipped,delivered,cancelled',
            'payment_status' => 'nullable|in:unpaid,paid,refunded',
        ]);

        $data = ['status' => $request->status];
        if ($request->filled('payment_status')) {
            $data['payment_status'] = $request->payment_status;
        }

        $order->update($data);

        return back()->with('success', 'Order updated successfully.');
    }
}
