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

        $orderTransitions = [
            'pending'    => ['confirmed', 'cancelled'],
            'confirmed'  => ['processing', 'cancelled'],
            'processing' => ['shipped', 'cancelled'],
            'shipped'    => ['delivered'],
            'delivered'  => [],
            'cancelled'  => [],
        ];

        $payTransitions = [
            'unpaid'   => ['paid'],
            'paid'     => ['refunded'],
            'refunded' => [],
        ];

        $newStatus = $request->status;
        if ($newStatus !== $order->status) {
            if (!in_array($newStatus, $orderTransitions[$order->status] ?? [])) {
                return back()->with('error', 'Invalid status transition. Order status can only move forward.');
            }
        }

        $data = ['status' => $newStatus];

        if ($request->filled('payment_status')) {
            $newPayStatus = $request->payment_status;
            if ($newPayStatus !== $order->payment_status) {
                if (!in_array($newPayStatus, $payTransitions[$order->payment_status] ?? [])) {
                    return back()->with('error', 'Invalid payment status transition. Payment status cannot be reversed.');
                }
            }
            $data['payment_status'] = $newPayStatus;
        }

        $order->update($data);

        return back()->with('success', 'Order updated successfully.');
    }
}
