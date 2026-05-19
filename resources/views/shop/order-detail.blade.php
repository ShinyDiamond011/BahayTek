@extends('layouts.app')
@section('title','Order #'.str_pad($order->id,5,'0',STR_PAD_LEFT))

@push('styles')
<style>
.page-hero{background:linear-gradient(135deg,#1a2e12 0%,#2a4a1e 50%,#336a29 100%);padding:40px 0 28px;position:relative}
.page-hero::before{content:'';position:absolute;inset:0;background:radial-gradient(ellipse 60% 80% at 80% 40%,rgba(193,217,92,.06) 0%,transparent 60%)}
.page-hero-inner{max-width:1120px;margin:0 auto;padding:0 40px;position:relative;z-index:1}
.breadcrumb{display:flex;align-items:center;gap:8px;font-size:.78rem;color:rgba(255,255,255,.6);margin-bottom:8px}
.breadcrumb a{color:rgba(255,255,255,.6);text-decoration:none}.breadcrumb a:hover{color:var(--mint)}
.page-hero h1{font-family:'DM Serif Display',serif;font-size:1.8rem;color:#fff}
.page-hero p{font-size:.82rem;color:rgba(255,255,255,.55);margin-top:4px}

.od-wrap{max-width:1120px;margin:0 auto;padding:32px 40px;display:grid;grid-template-columns:1fr 300px;gap:24px;align-items:start}
.od-card{background:#fff;border:1px solid var(--border);border-radius:16px;box-shadow:var(--sh-sm);overflow:hidden;margin-bottom:18px}
.od-card-title{padding:15px 20px;border-bottom:1px solid var(--border);font-size:.85rem;font-weight:700;color:var(--dark);display:flex;align-items:center;gap:7px}
.od-card-body{padding:20px}

/* Items table */
.items-row{display:grid;grid-template-columns:auto 1fr auto auto;gap:14px;align-items:center;padding:12px 0;border-bottom:1px solid var(--border)}
.items-row:last-child{border-bottom:none}
.ir-img{width:52px;height:52px;border-radius:9px;object-fit:cover;background:var(--sand);flex-shrink:0}
.ir-img-ph{width:52px;height:52px;border-radius:9px;background:var(--sand);display:flex;align-items:center;justify-content:center;flex-shrink:0}
.ir-name{font-size:.82rem;font-weight:700;color:var(--dark);margin-bottom:2px}
.ir-variant{font-size:.72rem;color:var(--gray)}
.ir-qty{font-size:.78rem;color:var(--gray);white-space:nowrap}
.ir-price{font-size:.88rem;font-weight:700;color:var(--dark);white-space:nowrap;text-align:right}

/* Address */
.addr-row{display:flex;gap:8px;margin-bottom:6px;font-size:.82rem}
.addr-label{font-weight:700;color:var(--gray);min-width:80px;flex-shrink:0}
.addr-val{color:var(--dark)}

/* Timeline */
.timeline{display:flex;flex-direction:column;gap:0}
.tl-item{display:flex;gap:14px;position:relative}
.tl-item:not(:last-child)::before{content:'';position:absolute;left:11px;top:22px;bottom:-4px;width:2px;background:var(--border)}
.tl-dot{width:24px;height:24px;border-radius:50%;flex-shrink:0;display:flex;align-items:center;justify-content:center;z-index:1}
.tl-dot.done{background:var(--forest)}
.tl-dot.active{background:var(--mint)}
.tl-dot.pending{background:var(--border)}
.tl-content{padding:2px 0 18px}
.tl-label{font-size:.82rem;font-weight:700;color:var(--dark)}
.tl-sub{font-size:.72rem;color:var(--gray)}

/* Sidebar summary */
.od-summary{background:#fff;border:1px solid var(--border);border-radius:16px;box-shadow:var(--sh-sm);padding:20px;position:sticky;top:80px}
.os-title{font-size:.85rem;font-weight:700;color:var(--dark);margin-bottom:14px;padding-bottom:12px;border-bottom:1px solid var(--border)}
.os-row{display:flex;justify-content:space-between;font-size:.82rem;color:var(--gray);margin-bottom:8px}
.os-total{display:flex;justify-content:space-between;padding-top:12px;margin-top:6px;border-top:1px solid var(--border);font-size:1rem;font-weight:700;color:var(--dark)}

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

@media(max-width:820px){
  .od-wrap{grid-template-columns:1fr;padding:20px 18px}
  .od-summary{position:static}
  .page-hero-inner{padding:0 18px}
}
</style>
@endpush

@section('content')
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
  $shippingAddr = json_decode($order->shipping_address ?? '{}', true) ?? [];
  $statuses = ['pending','confirmed','processing','shipped','delivered'];
  $currentIdx = array_search($order->status, $statuses);
@endphp

<div class="page-hero">
  <div class="page-hero-inner">
    <div class="breadcrumb">
      <a href="{{ route('shop.orders') }}">My Orders</a>
      <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
      <span>Order #{{ str_pad($order->id,5,'0',STR_PAD_LEFT) }}</span>
    </div>
    <h1>Order #{{ str_pad($order->id,5,'0',STR_PAD_LEFT) }}</h1>
    <p>Placed {{ $order->ordered_at->format('F j, Y \a\t g:i A') }}</p>
  </div>
</div>

<div class="od-wrap">
  <div>
    <!-- Items -->
    <div class="od-card">
      <div class="od-card-title">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="var(--forest)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 01-8 0"/></svg>
        Order Items
      </div>
      <div class="od-card-body">
        @foreach($order->items as $item)
        <div class="items-row">
          @if($item->variant?->product?->image_url)
            <img class="ir-img" src="{{ $item->variant->product->image_url }}" alt="">
          @else
            <div class="ir-img-ph">
              <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="var(--border)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21 16V8a2 2 0 00-1-1.73l-7-4a2 2 0 00-2 0l-7 4A2 2 0 003 8v8a2 2 0 001 1.73l7 4a2 2 0 002 0l7-4A2 2 0 0021 16z"/></svg>
            </div>
          @endif
          <div>
            <div class="ir-name">{{ $item->variant?->product?->prod_name ?? 'Product' }}</div>
            <div class="ir-variant">{{ $item->variant?->var_name }}</div>
          </div>
          <div class="ir-qty">× {{ $item->quantity }}</div>
          <div class="ir-price">₱{{ number_format($item->unit_price * $item->quantity, 2) }}</div>
        </div>
        @endforeach
      </div>
    </div>

    <!-- Shipping Address -->
    <div class="od-card">
      <div class="od-card-title">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="var(--forest)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg>
        Shipping Address
      </div>
      <div class="od-card-body">
        <div class="addr-row"><span class="addr-label">Name</span><span class="addr-val">{{ ($shippingAddr['first_name']??'') }} {{ ($shippingAddr['last_name']??'') }}</span></div>
        <div class="addr-row"><span class="addr-label">Phone</span><span class="addr-val">{{ $shippingAddr['phone'] ?? '—' }}</span></div>
        <div class="addr-row"><span class="addr-label">Email</span><span class="addr-val">{{ $shippingAddr['email'] ?? '—' }}</span></div>
        <div class="addr-row"><span class="addr-label">Address</span><span class="addr-val">{{ $shippingAddr['street'] ?? '' }}, {{ $shippingAddr['city'] ?? '' }}, {{ $shippingAddr['province'] ?? '' }}</span></div>
      </div>
    </div>

    @if($order->notes)
    <div class="od-card">
      <div class="od-card-title">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="var(--forest)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
        Order Notes
      </div>
      <div class="od-card-body" style="font-size:.84rem;color:var(--gray)">{{ $order->notes }}</div>
    </div>
    @endif

    <!-- Timeline -->
    @if($order->status !== 'cancelled')
    <div class="od-card">
      <div class="od-card-title">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="var(--forest)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
        Order Progress
      </div>
      <div class="od-card-body">
        <div class="timeline">
          @foreach($statuses as $idx => $status)
          @php
            $isDone   = $currentIdx !== false && $idx < $currentIdx;
            $isActive = $currentIdx !== false && $idx === $currentIdx;
          @endphp
          <div class="tl-item">
            <div class="tl-dot {{ $isDone ? 'done' : ($isActive ? 'active' : 'pending') }}">
              @if($isDone)
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
              @elseif($isActive)
                <div style="width:8px;height:8px;border-radius:50%;background:var(--dark)"></div>
              @endif
            </div>
            <div class="tl-content">
              <div class="tl-label" style="{{ $isActive ? 'color:var(--forest)' : '' }}">{{ ucfirst($status) }}</div>
              <div class="tl-sub">
                @if($isDone) Completed
                @elseif($isActive) Current status
                @else Upcoming
                @endif
              </div>
            </div>
          </div>
          @endforeach
        </div>
      </div>
    </div>
    @endif
  </div>

  <!-- Sidebar -->
  <div>
    <div class="od-summary">
      <div class="os-title">Order Summary</div>
      <div class="os-row"><span>Status</span><span class="badge {{ $statusClass }}">{{ ucfirst($order->status) }}</span></div>
      <div class="os-row"><span>Payment</span><span class="badge {{ $payClass }}">{{ ucfirst($order->payment_status) }}</span></div>
      <div class="os-row"><span>Method</span><span style="font-weight:600;color:var(--dark);text-transform:uppercase">{{ $order->payment_method }}</span></div>
      <div class="os-row"><span>Items</span><span style="font-weight:600;color:var(--dark)">{{ $order->items->count() }}</span></div>
      <div class="os-total">
        <span>Total</span>
        <span>₱{{ number_format($order->total_amount, 2) }}</span>
      </div>
      <div style="margin-top:18px">
        <a href="{{ route('shop.orders') }}" style="display:block;text-align:center;padding:10px;border-radius:10px;border:1.5px solid var(--border);font-size:.8rem;font-weight:700;color:var(--gray);text-decoration:none;transition:all .2s" onmouseover="this.style.borderColor='var(--forest)';this.style.color='var(--forest)'" onmouseout="this.style.borderColor='var(--border)';this.style.color='var(--gray)'">
          ← Back to Orders
        </a>
        <a href="{{ route('shop.index') }}" style="display:block;text-align:center;padding:10px;border-radius:10px;background:var(--forest);font-size:.8rem;font-weight:700;color:#fff;text-decoration:none;margin-top:8px;transition:background .2s" onmouseover="this.style.background='var(--green)'" onmouseout="this.style.background='var(--forest)'">
          Continue Shopping
        </a>
      </div>
    </div>
  </div>
</div>
@endsection
