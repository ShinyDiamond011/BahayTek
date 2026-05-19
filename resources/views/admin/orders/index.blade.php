@extends('layouts.admin')
@section('title','Orders')

@section('content')
<div class="page-header">
  <div>
    <h1 class="page-title">Orders</h1>
    <p class="page-sub">Manage customer orders and update their status.</p>
  </div>
</div>

@if(session('success'))
<div class="alert alert-success">{{ session('success') }}</div>
@endif

<!-- Status tabs -->
<div style="display:flex;gap:4px;flex-wrap:wrap;margin-bottom:18px;background:#fff;border:1px solid var(--border);border-radius:12px;padding:6px;box-shadow:var(--sh-sm)">
  @php
    $statuses = [
      'all'        => 'All',
      'pending'    => 'Pending',
      'confirmed'  => 'Confirmed',
      'processing' => 'Processing',
      'shipped'    => 'Shipped',
      'delivered'  => 'Delivered',
      'cancelled'  => 'Cancelled',
    ];
    $currentStatus = request('status','');
  @endphp
  @foreach($statuses as $val => $lbl)
  @php $isActive = ($val === 'all' && !request('status')) || request('status') === $val; @endphp
  <a href="{{ route('admin.orders.index') }}?{{ http_build_query(array_merge(request()->except(['status','page']), $val !== 'all' ? ['status'=>$val] : [])) }}"
    style="display:flex;align-items:center;gap:6px;padding:7px 14px;border-radius:9px;font-size:.78rem;font-weight:600;text-decoration:none;transition:all .2s;
    {{ $isActive ? 'background:var(--forest);color:#fff' : 'color:var(--gray)' }}">
    {{ $lbl }}
    <span style="font-size:.68rem;padding:1px 7px;border-radius:20px;{{ $isActive ? 'background:rgba(255,255,255,.25);color:#fff' : 'background:var(--sand);color:var(--gray)' }}">
      {{ $counts[$val] }}
    </span>
  </a>
  @endforeach
</div>

<!-- Search / Filters -->
<div class="card" style="margin-bottom:18px">
  <form method="GET" action="{{ route('admin.orders.index') }}" style="display:flex;gap:10px;align-items:flex-end;flex-wrap:wrap;padding:16px 20px">
    @if(request('status'))<input type="hidden" name="status" value="{{ request('status') }}">@endif
    <div style="flex:1;min-width:200px">
      <div style="font-size:.73rem;font-weight:700;color:var(--dark);margin-bottom:5px">Search Customer</div>
      <input class="form-control" type="text" name="search" placeholder="Name or email..." value="{{ request('search') }}">
    </div>
    <div style="min-width:140px">
      <div style="font-size:.73rem;font-weight:700;color:var(--dark);margin-bottom:5px">Payment</div>
      <select class="form-control form-select" name="payment">
        <option value="">All</option>
        <option value="unpaid" {{ request('payment')==='unpaid'?'selected':'' }}>Unpaid</option>
        <option value="paid" {{ request('payment')==='paid'?'selected':'' }}>Paid</option>
        <option value="refunded" {{ request('payment')==='refunded'?'selected':'' }}>Refunded</option>
      </select>
    </div>
    <button type="submit" class="btn btn-primary" style="height:38px">Filter</button>
    @if(request()->hasAny(['search','payment']))
      <a href="{{ route('admin.orders.index') }}{{ request('status') ? '?status='.request('status') : '' }}" class="btn btn-secondary" style="height:38px">Clear</a>
    @endif
  </form>
</div>

<div class="card">
  <div class="tbl-wrap">
    <table class="tbl">
      <thead>
        <tr>
          <th>Order</th>
          <th>Customer</th>
          <th>Items</th>
          <th>Total</th>
          <th>Status</th>
          <th>Payment</th>
          <th>Date</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        @forelse($orders as $order)
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
        <tr>
          <td>
            <div style="font-size:.82rem;font-weight:700;color:var(--dark)">#{{ str_pad($order->id,5,'0',STR_PAD_LEFT) }}</div>
            <div style="font-size:.7rem;color:var(--gray);text-transform:uppercase">{{ $order->payment_method }}</div>
          </td>
          <td>
            <div style="font-size:.82rem;font-weight:600;color:var(--dark)">{{ $order->user?->first_name }} {{ $order->user?->last_name }}</div>
            <div style="font-size:.72rem;color:var(--gray)">{{ $order->user?->email }}</div>
          </td>
          <td style="font-size:.82rem;color:var(--gray)">{{ $order->items->count() }} item{{ $order->items->count() !== 1 ? 's' : '' }}</td>
          <td><strong style="color:var(--dark)">₱{{ number_format($order->total_amount, 2) }}</strong></td>
          <td><span class="badge {{ $sc }}">{{ ucfirst($order->status) }}</span></td>
          <td><span class="badge {{ $pc }}">{{ ucfirst($order->payment_status) }}</span></td>
          <td style="font-size:.75rem;color:var(--gray)">{{ $order->ordered_at->format('M j, Y') }}</td>
          <td>
            <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-secondary btn-sm">View</a>
          </td>
        </tr>
        @empty
        <tr><td colspan="8" style="text-align:center;padding:40px;color:var(--gray)">No orders found.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
  @if($orders->hasPages())
  <div style="padding:16px 20px;border-top:1px solid var(--border)">{{ $orders->withQueryString()->links() }}</div>
  @endif
</div>
@endsection
