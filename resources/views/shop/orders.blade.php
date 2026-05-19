@extends('layouts.app')
@section('title','My Orders')

@push('styles')
<style>
.page-hero{background:linear-gradient(135deg,#1a2e12 0%,#2a4a1e 50%,#336a29 100%);padding:40px 0 28px;position:relative}
.page-hero::before{content:'';position:absolute;inset:0;background:radial-gradient(ellipse 60% 80% at 80% 40%,rgba(193,217,92,.06) 0%,transparent 60%)}
.page-hero-inner{max-width:1120px;margin:0 auto;padding:0 40px;position:relative;z-index:1}
.page-hero h1{font-family:'DM Serif Display',serif;font-size:1.8rem;color:#fff}
.page-hero p{font-size:.82rem;color:rgba(255,255,255,.55);margin-top:4px}

.orders-wrap{max-width:1120px;margin:0 auto;padding:32px 40px}

.order-card{background:#fff;border:1px solid var(--border);border-radius:16px;box-shadow:var(--sh-sm);overflow:hidden;margin-bottom:16px;transition:box-shadow .2s}
.order-card:hover{box-shadow:var(--sh-md)}
.order-head{padding:16px 20px;border-bottom:1px solid var(--border);display:flex;align-items:center;justify-content:space-between;gap:12px;flex-wrap:wrap}
.order-id{font-size:.82rem;font-weight:700;color:var(--dark)}
.order-date{font-size:.75rem;color:var(--gray)}
.order-body{padding:16px 20px}
.order-items-preview{display:flex;gap:8px;margin-bottom:12px;flex-wrap:wrap}
.item-thumb{width:48px;height:48px;border-radius:8px;object-fit:cover;background:var(--sand)}
.item-thumb-ph{width:48px;height:48px;border-radius:8px;background:var(--sand);display:flex;align-items:center;justify-content:center}
.item-thumb-more{width:48px;height:48px;border-radius:8px;background:var(--sand);display:flex;align-items:center;justify-content:center;font-size:.7rem;font-weight:700;color:var(--gray)}
.order-meta{display:flex;align-items:center;gap:14px;flex-wrap:wrap}
.order-amount{font-size:.95rem;font-weight:700;color:var(--dark)}
.order-label{font-size:.68rem;font-weight:600;color:var(--gray)}
.order-foot{padding:12px 20px;background:var(--sand);border-top:1px solid var(--border);display:flex;align-items:center;justify-content:space-between;gap:12px}
.order-foot a{font-size:.8rem;font-weight:700;color:var(--forest);text-decoration:none;display:flex;align-items:center;gap:4px}
.order-foot a:hover{color:var(--green)}

/* Status badges */
.badge{font-size:.62rem;font-weight:700;letter-spacing:.5px;text-transform:uppercase;padding:3px 10px;border-radius:20px}
.badge-pending{background:#fef9c3;color:#854d0e;border:1px solid #fde68a}
.badge-confirmed{background:#dbeafe;color:#1e40af;border:1px solid #93c5fd}
.badge-processing{background:#ede9fe;color:#5b21b6;border:1px solid #c4b5fd}
.badge-shipped{background:#d1fae5;color:#065f46;border:1px solid #6ee7b7}
.badge-delivered{background:#dcfce7;color:#166534;border:1px solid #86efac}
.badge-cancelled{background:#fee2e2;color:#991b1b;border:1px solid #fca5a5}
.badge-unpaid{background:#fef9c3;color:#854d0e;border:1px solid #fde68a}
.badge-paid{background:#dcfce7;color:#166534;border:1px solid #86efac}
.badge-refunded{background:#f3f4f6;color:#374151;border:1px solid #d1d5db}

.empty-state{text-align:center;padding:72px 24px}
.empty-icon{width:80px;height:80px;border-radius:50%;background:var(--sand);display:flex;align-items:center;justify-content:center;margin:0 auto 18px}

@media(max-width:640px){
  .orders-wrap{padding:20px 18px}
  .page-hero-inner{padding:0 18px}
  .order-head{flex-direction:column;align-items:flex-start;gap:6px}
}
</style>
@endpush

@section('content')
<div class="page-hero">
  <div class="page-hero-inner">
    <h1>My Orders</h1>
    <p>Track and manage your BAHAYTEK purchases.</p>
  </div>
</div>

<div class="orders-wrap">
  @if(session('success'))
  <div style="background:#dcfce7;border:1px solid #86efac;color:#166534;padding:12px 18px;border-radius:10px;font-size:.84rem;font-weight:600;margin-bottom:20px;display:flex;align-items:center;gap:8px">
    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
    {{ session('success') }}
  </div>
  @endif

  @if($orders->isEmpty())
  <div class="empty-state">
    <div class="empty-icon">
      <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="var(--emerald)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 01-8 0"/></svg>
    </div>
    <div style="font-size:1.15rem;font-weight:700;color:var(--dark);margin-bottom:8px">No orders yet</div>
    <div style="font-size:.85rem;color:var(--gray);margin-bottom:24px">Your order history will appear here once you make a purchase.</div>
    <a href="{{ route('shop.index') }}" style="display:inline-flex;align-items:center;gap:6px;padding:11px 24px;border-radius:10px;background:var(--forest);color:#fff;text-decoration:none;font-size:.85rem;font-weight:700">
      Start Shopping
    </a>
  </div>
  @else
  @foreach($orders as $order)
  @php
    $statusClass = match($order->status){
      'confirmed'  => 'badge-confirmed',
      'processing' => 'badge-processing',
      'shipped'    => 'badge-shipped',
      'delivered'  => 'badge-delivered',
      'cancelled'  => 'badge-cancelled',
      default      => 'badge-pending',
    };
    $payClass = match($order->payment_status){
      'paid'     => 'badge-paid',
      'refunded' => 'badge-refunded',
      default    => 'badge-unpaid',
    };
  @endphp
  <div class="order-card">
    <div class="order-head">
      <div>
        <div class="order-id">Order #{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</div>
        <div class="order-date">{{ $order->ordered_at->format('F j, Y · g:i A') }}</div>
      </div>
      <div style="display:flex;gap:6px;flex-wrap:wrap;align-items:center">
        <span class="badge {{ $statusClass }}">{{ ucfirst($order->status) }}</span>
        <span class="badge {{ $payClass }}">{{ ucfirst($order->payment_status) }}</span>
      </div>
    </div>
    <div class="order-body">
      <div class="order-items-preview">
        @foreach($order->items->take(4) as $item)
          @if($item->variant?->product?->image_url)
            <img class="item-thumb" src="{{ $item->variant->product->image_url }}" alt="{{ $item->variant->product->prod_name }}">
          @else
            <div class="item-thumb-ph">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="var(--border)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21 16V8a2 2 0 00-1-1.73l-7-4a2 2 0 00-2 0l-7 4A2 2 0 003 8v8a2 2 0 001 1.73l7 4a2 2 0 002 0l7-4A2 2 0 0021 16z"/></svg>
            </div>
          @endif
        @endforeach
        @if($order->items->count() > 4)
          <div class="item-thumb-more">+{{ $order->items->count() - 4 }}</div>
        @endif
      </div>
      <div class="order-meta">
        <div>
          <div class="order-label">Total Amount</div>
          <div class="order-amount">₱{{ number_format($order->total_amount, 2) }}</div>
        </div>
        <div>
          <div class="order-label">Items</div>
          <div style="font-size:.88rem;font-weight:600;color:var(--dark)">{{ $order->items->count() }} item{{ $order->items->count() !== 1 ? 's' : '' }}</div>
        </div>
        <div>
          <div class="order-label">Payment</div>
          <div style="font-size:.88rem;font-weight:600;color:var(--dark);text-transform:uppercase">{{ $order->payment_method }}</div>
        </div>
      </div>
    </div>
    <div class="order-foot">
      <span style="font-size:.78rem;color:var(--gray)">
        @if($order->status === 'delivered') Delivered
        @elseif($order->status === 'shipped') On its way
        @elseif($order->status === 'cancelled') Order cancelled
        @else Being processed
        @endif
      </span>
      <a href="{{ route('shop.order.detail', $order) }}">
        View Details
        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
      </a>
    </div>
  </div>
  @endforeach

  @if($orders->hasPages())
  <div style="margin-top:16px">{{ $orders->links() }}</div>
  @endif
  @endif
</div>
@endsection
