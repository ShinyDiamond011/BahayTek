@extends('layouts.admin')
@section('title','Order #'.str_pad($order->id,5,'0',STR_PAD_LEFT))

@section('content')
@php
  $sc = match($order->status){
    'confirmed'  => 'badge-info',
    'processing' => 'badge-warning',
    'shipped'    => 'badge-success',
    'delivered'  => 'badge-success',
    'cancelled'  => 'badge-danger',
    default      => 'badge-secondary',
  };
  $pc = match($order->payment_status){
    'paid'     => 'badge-success',
    'refunded' => 'badge-secondary',
    default    => 'badge-warning',
  };
@endphp

<div class="page-header">
  <div style="display:flex;align-items:center;gap:12px">
    <a href="{{ route('admin.orders.index') }}" style="display:flex;align-items:center;color:var(--gray);text-decoration:none;font-size:.82rem;font-weight:600">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg>
      Orders
    </a>
    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="var(--border)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
    <h1 class="page-title" style="margin:0;font-size:1.1rem">Order #{{ str_pad($order->id,5,'0',STR_PAD_LEFT) }}</h1>
  </div>
  <div style="display:flex;gap:8px;align-items:center">
    <span class="badge {{ $sc }}" style="font-size:.7rem;padding:4px 12px">{{ ucfirst($order->status) }}</span>
    <span class="badge {{ $pc }}" style="font-size:.7rem;padding:4px 12px">{{ ucfirst($order->payment_status) }}</span>
  </div>
</div>

@if(session('success'))
<div class="alert alert-success">{{ session('success') }}</div>
@endif

<div style="display:grid;grid-template-columns:1fr 300px;gap:20px;align-items:start">
  <div>
    <!-- Items -->
    <div class="card" style="margin-bottom:18px">
      <div style="padding:15px 20px;border-bottom:1px solid var(--border);font-size:.85rem;font-weight:700;color:var(--dark)">Order Items</div>
      <div class="tbl-wrap">
        <table class="tbl">
          <thead>
            <tr>
              <th>Product</th>
              <th>Variant</th>
              <th>Qty</th>
              <th>Unit Price</th>
              <th>Subtotal</th>
            </tr>
          </thead>
          <tbody>
            @foreach($order->items as $item)
            <tr>
              <td>
                <div style="display:flex;align-items:center;gap:10px">
                  @if($item->variant?->product?->image_url)
                    <img src="{{ $item->variant->product->image_url }}" style="width:38px;height:38px;border-radius:8px;object-fit:cover;flex-shrink:0">
                  @else
                    <div style="width:38px;height:38px;border-radius:8px;background:var(--sand);flex-shrink:0;display:flex;align-items:center;justify-content:center">
                      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="var(--border)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21 16V8a2 2 0 00-1-1.73l-7-4a2 2 0 00-2 0l-7 4A2 2 0 003 8v8a2 2 0 001 1.73l7 4a2 2 0 002 0l7-4A2 2 0 0021 16z"/></svg>
                    </div>
                  @endif
                  <div style="font-size:.82rem;font-weight:600;color:var(--dark)">{{ $item->variant?->product?->prod_name ?? 'Deleted Product' }}</div>
                </div>
              </td>
              <td style="font-size:.78rem;color:var(--gray)">{{ $item->variant?->var_name ?? '—' }}</td>
              <td style="font-size:.82rem;font-weight:600;color:var(--dark)">{{ $item->quantity }}</td>
              <td style="font-size:.82rem;color:var(--gray)">₱{{ number_format($item->unit_price, 2) }}</td>
              <td><strong style="color:var(--dark)">₱{{ number_format($item->unit_price * $item->quantity, 2) }}</strong></td>
            </tr>
            @endforeach
          </tbody>
          <tfoot>
            <tr>
              <td colspan="4" style="text-align:right;font-size:.82rem;font-weight:700;color:var(--dark);padding:12px 16px">Total</td>
              <td style="padding:12px 16px"><strong style="color:var(--forest);font-size:.95rem">₱{{ number_format($order->total_amount, 2) }}</strong></td>
            </tr>
          </tfoot>
        </table>
      </div>
    </div>

    <!-- Shipping Address -->
    <div class="card" style="margin-bottom:18px">
      <div style="padding:15px 20px;border-bottom:1px solid var(--border);font-size:.85rem;font-weight:700;color:var(--dark)">Shipping Address</div>
      <div style="padding:18px 20px">
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;font-size:.83rem">
          <div><span style="font-weight:700;color:var(--gray);display:block;font-size:.7rem;margin-bottom:2px">NAME</span>{{ ($address['first_name']??'') }} {{ ($address['last_name']??'') }}</div>
          <div><span style="font-weight:700;color:var(--gray);display:block;font-size:.7rem;margin-bottom:2px">PHONE</span>{{ $address['phone'] ?? '—' }}</div>
          <div><span style="font-weight:700;color:var(--gray);display:block;font-size:.7rem;margin-bottom:2px">EMAIL</span>{{ $address['email'] ?? '—' }}</div>
          <div><span style="font-weight:700;color:var(--gray);display:block;font-size:.7rem;margin-bottom:2px">PROVINCE</span>{{ $address['province'] ?? '—' }}</div>
          <div style="grid-column:1/-1"><span style="font-weight:700;color:var(--gray);display:block;font-size:.7rem;margin-bottom:2px">STREET / CITY</span>{{ $address['street'] ?? '' }}, {{ $address['city'] ?? '' }}</div>
        </div>
        @if($order->notes)
        <div style="margin-top:14px;padding-top:14px;border-top:1px solid var(--border)">
          <span style="font-weight:700;color:var(--gray);display:block;font-size:.7rem;margin-bottom:4px">ORDER NOTES</span>
          <span style="font-size:.83rem;color:var(--dark)">{{ $order->notes }}</span>
        </div>
        @endif
      </div>
    </div>

    <!-- Customer -->
    <div class="card">
      <div style="padding:15px 20px;border-bottom:1px solid var(--border);font-size:.85rem;font-weight:700;color:var(--dark)">Customer</div>
      <div style="padding:18px 20px;font-size:.83rem">
        <div style="font-weight:700;color:var(--dark);margin-bottom:3px">{{ $order->user?->first_name }} {{ $order->user?->last_name }}</div>
        <div style="color:var(--gray);margin-bottom:3px">{{ $order->user?->email }}</div>
        <div style="color:var(--gray)">{{ $order->user?->phone }}</div>
      </div>
    </div>
  </div>

  <!-- Sidebar: Update Status -->
  <div>
    <div class="card">
      <div style="padding:15px 20px;border-bottom:1px solid var(--border);font-size:.85rem;font-weight:700;color:var(--dark)">Update Order</div>
      <form method="POST" action="{{ route('admin.orders.updateStatus', $order) }}" style="padding:18px 20px">
        @csrf @method('PUT')
        <div style="margin-bottom:14px">
          <div style="font-size:.73rem;font-weight:700;color:var(--dark);margin-bottom:6px">Order Status</div>
          <select class="form-control form-select" name="status" style="width:100%">
            @foreach(['pending','confirmed','processing','shipped','delivered','cancelled'] as $s)
            <option value="{{ $s }}" {{ $order->status === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
            @endforeach
          </select>
        </div>
        <div style="margin-bottom:16px">
          <div style="font-size:.73rem;font-weight:700;color:var(--dark);margin-bottom:6px">Payment Status</div>
          <select class="form-control form-select" name="payment_status" style="width:100%">
            @foreach(['unpaid','paid','refunded'] as $ps)
            <option value="{{ $ps }}" {{ $order->payment_status === $ps ? 'selected' : '' }}>{{ ucfirst($ps) }}</option>
            @endforeach
          </select>
        </div>
        <button type="submit" class="btn btn-primary" style="width:100%">Update</button>
      </form>
    </div>

    <div class="card" style="margin-top:16px">
      <div style="padding:15px 20px;border-bottom:1px solid var(--border);font-size:.85rem;font-weight:700;color:var(--dark)">Order Info</div>
      <div style="padding:18px 20px">
        @foreach([
          'ID'      => '#'.str_pad($order->id,5,'0',STR_PAD_LEFT),
          'Date'    => $order->ordered_at->format('M j, Y g:i A'),
          'Method'  => strtoupper($order->payment_method),
          'PayMongo' => $order->paymongo_payment_id ?? 'N/A',
        ] as $lbl => $val)
        <div style="display:flex;justify-content:space-between;font-size:.78rem;margin-bottom:8px">
          <span style="color:var(--gray)">{{ $lbl }}</span>
          <span style="font-weight:600;color:var(--dark)">{{ $val }}</span>
        </div>
        @endforeach
      </div>
    </div>
  </div>
</div>
@endsection
