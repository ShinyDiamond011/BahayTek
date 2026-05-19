@extends('layouts.admin')
@section('title','Dashboard')

@push('styles')
<style>
.stat-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:16px;margin-bottom:24px}
.stat-card{background:#fff;border:1px solid var(--border);border-radius:14px;padding:18px 20px;box-shadow:var(--sh-sm)}
.stat-label{font-size:.72rem;font-weight:700;color:var(--gray);letter-spacing:.5px;text-transform:uppercase;margin-bottom:6px}
.stat-value{font-size:1.8rem;font-weight:700;color:var(--dark);line-height:1}
.stat-sub{font-size:.72rem;color:var(--gray);margin-top:5px}
.stat-accent{display:inline-flex;align-items:center;gap:4px;font-size:.72rem;font-weight:700;padding:2px 8px;border-radius:20px;margin-top:6px}
.stat-up{background:#dcfce7;color:#166534}
.stat-warn{background:#fef9c3;color:#854d0e}
.stat-danger{background:#fee2e2;color:#991b1b}
.stat-icon{width:36px;height:36px;border-radius:10px;display:flex;align-items:center;justify-content:center;margin-bottom:10px}

.dash-grid{display:grid;grid-template-columns:1fr 1fr;gap:20px;margin-bottom:20px}
.dash-card{background:#fff;border:1px solid var(--border);border-radius:14px;box-shadow:var(--sh-sm);overflow:hidden}
.dash-card-title{padding:14px 18px;border-bottom:1px solid var(--border);font-size:.84rem;font-weight:700;color:var(--dark);display:flex;align-items:center;justify-content:space-between}
.dash-card-title a{font-size:.73rem;font-weight:600;color:var(--forest);text-decoration:none}
.dash-card-title a:hover{color:var(--green)}

.mini-order-row{display:flex;align-items:center;justify-content:space-between;padding:10px 18px;border-bottom:1px solid #eef2ea;font-size:.8rem;gap:12px}
.mini-order-row:last-child{border-bottom:none}
.mo-id{font-weight:700;color:var(--dark);font-family:monospace}
.mo-customer{color:var(--gray);flex:1;min-width:0;overflow:hidden;text-overflow:ellipsis;white-space:nowrap}
.mo-amount{font-weight:700;color:var(--dark);white-space:nowrap}

.mini-booking-row{display:flex;align-items:center;justify-content:space-between;padding:10px 18px;border-bottom:1px solid #eef2ea;font-size:.8rem;gap:12px}
.mini-booking-row:last-child{border-bottom:none}

/* Revenue bars */
.rev-bar-wrap{padding:16px 18px}
.rev-bar-row{display:flex;align-items:center;gap:10px;margin-bottom:10px;font-size:.78rem}
.rev-bar-label{min-width:52px;color:var(--gray);text-align:right}
.rev-bar-bg{flex:1;height:8px;border-radius:4px;background:var(--sand);overflow:hidden}
.rev-bar-fill{height:100%;border-radius:4px;background:var(--forest);transition:width .5s}
.rev-bar-val{min-width:80px;color:var(--dark);font-weight:600;font-size:.73rem}

/* Status donut placeholder */
.status-list{padding:14px 18px}
.status-row{display:flex;justify-content:space-between;align-items:center;padding:6px 0;border-bottom:1px solid #eef2ea;font-size:.8rem}
.status-row:last-child{border-bottom:none}
.status-dot{width:8px;height:8px;border-radius:50%;display:inline-block;margin-right:6px}

@media(max-width:1100px){.stat-grid{grid-template-columns:repeat(2,1fr)}}
@media(max-width:900px){.dash-grid{grid-template-columns:1fr}}
@media(max-width:640px){.stat-grid{grid-template-columns:1fr 1fr}}
</style>
@endpush

@section('content')
<div class="page-header">
  <div>
    <h1 class="page-title">Dashboard</h1>
    <p class="page-sub">{{ now()->format('l, F j, Y') }} — Overview of BAHAYTEK operations.</p>
  </div>
</div>

<!-- Stat Cards -->
<div class="stat-grid">
  <div class="stat-card">
    <div class="stat-icon" style="background:#dcfce7">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="var(--forest)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
    </div>
    <div class="stat-label">Total Users</div>
    <div class="stat-value">{{ number_format($stats['total_users']) }}</div>
    <div class="stat-sub">+{{ $stats['new_users_month'] }} this month</div>
  </div>
  <div class="stat-card">
    <div class="stat-icon" style="background:#dbeafe">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#1d4ed8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 01-8 0"/></svg>
    </div>
    <div class="stat-label">Total Orders</div>
    <div class="stat-value">{{ number_format($stats['total_orders']) }}</div>
    @if($stats['pending_orders'] > 0)
    <span class="stat-accent stat-warn">{{ $stats['pending_orders'] }} pending</span>
    @else
    <div class="stat-sub">{{ $stats['orders_today'] }} today</div>
    @endif
  </div>
  <div class="stat-card">
    <div class="stat-icon" style="background:#fef9c3">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#854d0e" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6"/></svg>
    </div>
    <div class="stat-label">Revenue (Month)</div>
    <div class="stat-value" style="font-size:1.4rem">₱{{ number_format($stats['revenue_month'], 0) }}</div>
    <div class="stat-sub">₱{{ number_format($stats['revenue_total'], 0) }} all time</div>
  </div>
  <div class="stat-card">
    <div class="stat-icon" style="background:#ede9fe">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#6d28d9" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
    </div>
    <div class="stat-label">Pending Bookings</div>
    <div class="stat-value">{{ $stats['pending_bookings'] }}</div>
    <div class="stat-sub">{{ $stats['pending_enrollments'] }} workshop enrollments pending</div>
  </div>
</div>

<!-- Second row stats -->
<div style="display:grid;grid-template-columns:repeat(3,1fr);gap:14px;margin-bottom:24px">
  <div class="stat-card" style="display:flex;align-items:center;gap:16px;padding:14px 18px">
    <div style="width:32px;height:32px;border-radius:8px;background:var(--sand);display:flex;align-items:center;justify-content:center;flex-shrink:0">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="var(--forest)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 16V8a2 2 0 00-1-1.73l-7-4a2 2 0 00-2 0l-7 4A2 2 0 003 8v8a2 2 0 001 1.73l7 4a2 2 0 002 0l7-4A2 2 0 0021 16z"/></svg>
    </div>
    <div>
      <div class="stat-label" style="margin-bottom:2px">Active Products</div>
      <div style="font-size:1.2rem;font-weight:700;color:var(--dark)">{{ $stats['active_products'] }}</div>
    </div>
    <div style="margin-left:auto;text-align:right">
      @if($stats['low_stock'] > 0)
        <span class="stat-accent stat-warn">{{ $stats['low_stock'] }} low</span>
      @endif
      @if($stats['out_of_stock'] > 0)
        <span class="stat-accent stat-danger">{{ $stats['out_of_stock'] }} out</span>
      @endif
    </div>
  </div>
  <div class="stat-card" style="display:flex;align-items:center;gap:16px;padding:14px 18px">
    <div style="width:32px;height:32px;border-radius:8px;background:#d1fae5;display:flex;align-items:center;justify-content:center;flex-shrink:0">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#065f46" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/></svg>
    </div>
    <div>
      <div class="stat-label" style="margin-bottom:2px">Upcoming Workshops</div>
      <div style="font-size:1.2rem;font-weight:700;color:var(--dark)">{{ $stats['upcoming_workshops'] }}</div>
    </div>
  </div>
  <div class="stat-card" style="display:flex;align-items:center;gap:16px;padding:14px 18px">
    <div style="width:32px;height:32px;border-radius:8px;background:#fee2e2;display:flex;align-items:center;justify-content:center;flex-shrink:0">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#991b1b" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
    </div>
    <div>
      <div class="stat-label" style="margin-bottom:2px">Needs Attention</div>
      <div style="font-size:1.2rem;font-weight:700;color:var(--dark)">{{ $stats['pending_orders'] + $stats['pending_bookings'] }}</div>
    </div>
    <div style="margin-left:auto;font-size:.72rem;color:var(--gray)">orders + bookings</div>
  </div>
</div>

<!-- Main content grid -->
<div class="dash-grid">
  <!-- Recent Orders -->
  <div class="dash-card">
    <div class="dash-card-title">
      Recent Orders
      <a href="{{ route('admin.orders.index') }}">View all →</a>
    </div>
    @foreach($recentOrders as $order)
    @php
      $sc = match($order->status){
        'confirmed'=>'badge-info','processing'=>'badge-warning',
        'shipped'=>'badge-success','delivered'=>'badge-success',
        'cancelled'=>'badge-danger',default=>'badge-secondary'
      };
    @endphp
    <div class="mini-order-row">
      <span class="mo-id">#{{ str_pad($order->id,5,'0',STR_PAD_LEFT) }}</span>
      <span class="mo-customer">{{ $order->user?->first_name }} {{ $order->user?->last_name }}</span>
      <span class="badge {{ $sc }}" style="font-size:.58rem;padding:2px 7px">{{ ucfirst($order->status) }}</span>
      <span class="mo-amount">₱{{ number_format($order->total_amount, 0) }}</span>
    </div>
    @endforeach
    @if($recentOrders->isEmpty())
    <div style="padding:24px;text-align:center;color:var(--gray);font-size:.8rem">No orders yet.</div>
    @endif
  </div>

  <!-- Revenue Chart (bar) -->
  <div class="dash-card">
    <div class="dash-card-title">
      Revenue — Last 6 Months
      <a href="{{ route('admin.reports.index') }}">Full Report →</a>
    </div>
    <div class="rev-bar-wrap">
      @php
        $maxRev = $revenueChart->max('total') ?: 1;
      @endphp
      @forelse($revenueChart as $row)
      <div class="rev-bar-row">
        <div class="rev-bar-label">{{ \Carbon\Carbon::createFromFormat('Y-m', $row->month)->format('M') }}</div>
        <div class="rev-bar-bg">
          <div class="rev-bar-fill" style="width:{{ round($row->total / $maxRev * 100) }}%"></div>
        </div>
        <div class="rev-bar-val">₱{{ number_format($row->total, 0) }}</div>
      </div>
      @empty
      <div style="text-align:center;color:var(--gray);font-size:.8rem;padding:24px">No revenue data yet.</div>
      @endforelse
    </div>
  </div>

  <!-- Recent Bookings -->
  <div class="dash-card">
    <div class="dash-card-title">
      Recent Consultation Bookings
      <a href="{{ route('admin.bookings.index') }}">View all →</a>
    </div>
    @foreach($recentBookings as $bk)
    @php
      $bc = match($bk->status){
        'confirmed'=>'badge-confirmed','completed'=>'badge-completed',
        'declined'=>'badge-declined','cancelled'=>'badge-cancelled',
        default=>'badge-pending'
      };
    @endphp
    <div class="mini-booking-row">
      <div style="flex:1;min-width:0">
        <div style="font-size:.8rem;font-weight:700;color:var(--dark);overflow:hidden;text-overflow:ellipsis;white-space:nowrap">{{ $bk->first_name }} {{ $bk->last_name }}</div>
        <div style="font-size:.7rem;color:var(--gray)">{{ $bk->schedule?->date?->format('M j, Y') }} · {{ $bk->schedule?->time_range }}</div>
      </div>
      <span class="badge {{ $bc }}">{{ ucfirst($bk->status) }}</span>
    </div>
    @endforeach
    @if($recentBookings->isEmpty())
    <div style="padding:24px;text-align:center;color:var(--gray);font-size:.8rem">No bookings yet.</div>
    @endif
  </div>

  <!-- Order Status breakdown -->
  <div class="dash-card">
    <div class="dash-card-title">
      Order Status Breakdown
      <a href="{{ route('admin.orders.index') }}">View orders →</a>
    </div>
    <div class="status-list">
      @php
        $statusDots = [
          'pending'    => '#f59e0b',
          'confirmed'  => '#3b82f6',
          'processing' => '#8b5cf6',
          'shipped'    => '#10b981',
          'delivered'  => '#166534',
          'cancelled'  => '#9ca3af',
        ];
        $totalOrders = $orderStatusBreakdown->sum() ?: 1;
      @endphp
      @foreach($statusDots as $status => $color)
      @php $cnt = $orderStatusBreakdown[$status] ?? 0; @endphp
      <div class="status-row">
        <div style="display:flex;align-items:center">
          <span class="status-dot" style="background:{{ $color }}"></span>
          <span style="font-size:.8rem;color:var(--dark)">{{ ucfirst($status) }}</span>
        </div>
        <div style="display:flex;align-items:center;gap:10px">
          <div style="width:80px;height:5px;border-radius:3px;background:var(--sand);overflow:hidden">
            <div style="width:{{ round($cnt / $totalOrders * 100) }}%;height:100%;background:{{ $color }};border-radius:3px"></div>
          </div>
          <span style="font-size:.78rem;font-weight:600;color:var(--dark);min-width:20px;text-align:right">{{ $cnt }}</span>
        </div>
      </div>
      @endforeach
    </div>
  </div>
</div>
@endsection
