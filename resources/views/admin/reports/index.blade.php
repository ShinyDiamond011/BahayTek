@extends('layouts.admin')
@section('title','Reports')

@push('styles')
<style>
.report-stat-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:14px;margin-bottom:22px}
.rstat{background:#fff;border:1px solid var(--border);border-radius:12px;padding:16px 18px;box-shadow:var(--sh-sm)}
.rstat-label{font-size:.7rem;font-weight:700;color:var(--gray);text-transform:uppercase;letter-spacing:.5px;margin-bottom:5px}
.rstat-value{font-size:1.6rem;font-weight:700;color:var(--dark);line-height:1}
.rstat-sub{font-size:.72rem;color:var(--gray);margin-top:4px}

.period-tabs{display:flex;gap:4px;background:#fff;border:1px solid var(--border);border-radius:10px;padding:4px;margin-bottom:20px;width:fit-content;box-shadow:var(--sh-sm)}
.period-tab{padding:6px 16px;border-radius:7px;font-size:.78rem;font-weight:600;color:var(--gray);text-decoration:none;transition:all .2s}
.period-tab.active{background:var(--forest);color:#fff}

@media(max-width:900px){.report-stat-grid{grid-template-columns:repeat(2,1fr)}}
</style>
@endpush

@section('content')
<div class="page-header">
  <div>
    <h1 class="page-title">Reports &amp; Analytics</h1>
    <p class="page-sub">Sales, operations, and inventory summary.</p>
  </div>
  <div style="display:flex;gap:8px">
    <a href="{{ route('admin.reports.export') }}?type=orders&period={{ $period }}" class="btn btn-secondary">
      <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
      Export Orders CSV
    </a>
    <a href="{{ route('admin.reports.export') }}?type=products" class="btn btn-secondary">
      <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
      Export Products CSV
    </a>
  </div>
</div>

<!-- Period selector -->
<div class="period-tabs">
  @foreach(['7'=>'7 Days','30'=>'30 Days','90'=>'90 Days','365'=>'1 Year'] as $days => $lbl)
  <a href="{{ route('admin.reports.index') }}?period={{ $days }}" class="period-tab {{ $period == $days ? 'active' : '' }}">{{ $lbl }}</a>
  @endforeach
</div>

<!-- Summary stats -->
<div class="report-stat-grid">
  <div class="rstat">
    <div class="rstat-label">Total Orders</div>
    <div class="rstat-value">{{ number_format($salesSummary['total_orders']) }}</div>
    <div class="rstat-sub">{{ $salesSummary['paid_orders'] }} paid</div>
  </div>
  <div class="rstat">
    <div class="rstat-label">Revenue</div>
    <div class="rstat-value" style="font-size:1.3rem">₱{{ number_format($salesSummary['total_revenue'], 0) }}</div>
    <div class="rstat-sub">from paid orders</div>
  </div>
  <div class="rstat">
    <div class="rstat-label">Avg. Order Value</div>
    <div class="rstat-value" style="font-size:1.3rem">₱{{ number_format($salesSummary['avg_order'], 0) }}</div>
    <div class="rstat-sub">per paid order</div>
  </div>
  <div class="rstat">
    <div class="rstat-label">New Users</div>
    <div class="rstat-value">{{ $newUsers }}</div>
    <div class="rstat-sub">registered in period</div>
  </div>
</div>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;margin-bottom:20px">
  <!-- Revenue by day -->
  <div class="card">
    <div style="padding:14px 18px;border-bottom:1px solid var(--border);font-size:.84rem;font-weight:700;color:var(--dark)">Daily Revenue</div>
    <div style="padding:16px 18px">
      @php $maxDay = $revenueByDay->max('total') ?: 1; @endphp
      @forelse($revenueByDay->take(14) as $row)
      <div style="display:flex;align-items:center;gap:10px;margin-bottom:8px;font-size:.75rem">
        <div style="min-width:44px;color:var(--gray);text-align:right">{{ \Carbon\Carbon::parse($row->day)->format('M j') }}</div>
        <div style="flex:1;height:7px;border-radius:3px;background:var(--sand);overflow:hidden">
          <div style="width:{{ round($row->total / $maxDay * 100) }}%;height:100%;background:var(--forest);border-radius:3px"></div>
        </div>
        <div style="min-width:70px;font-weight:600;color:var(--dark);font-size:.72rem">₱{{ number_format($row->total,0) }}</div>
        <div style="color:var(--gray);min-width:40px;font-size:.68rem">{{ $row->orders }} ord.</div>
      </div>
      @empty
      <div style="text-align:center;color:var(--gray);font-size:.8rem;padding:24px">No revenue in this period.</div>
      @endforelse
    </div>
  </div>

  <!-- Top Products -->
  <div class="card">
    <div style="padding:14px 18px;border-bottom:1px solid var(--border);font-size:.84rem;font-weight:700;color:var(--dark)">Top Products by Units Sold</div>
    <div class="tbl-wrap">
      <table class="tbl">
        <thead><tr><th>#</th><th>Product</th><th>Category</th><th>Units</th><th>Revenue</th></tr></thead>
        <tbody>
        @forelse($topProducts as $i => $p)
        <tr>
          <td style="font-size:.8rem;font-weight:700;color:var(--gray)">{{ $i+1 }}</td>
          <td style="font-size:.8rem;font-weight:600;color:var(--dark)">{{ Str::limit($p->prod_name, 28) }}</td>
          <td style="font-size:.72rem;color:var(--gray)">{{ $p->category }}</td>
          <td style="font-size:.82rem;font-weight:700;color:var(--dark)">{{ number_format($p->units_sold) }}</td>
          <td style="font-size:.78rem;font-weight:600;color:var(--forest)">₱{{ number_format($p->revenue,0) }}</td>
        </tr>
        @empty
        <tr><td colspan="5" style="text-align:center;padding:24px;color:var(--gray)">No sales in this period.</td></tr>
        @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>

<div style="display:grid;grid-template-columns:repeat(3,1fr);gap:20px;margin-bottom:20px">
  <!-- Order Status -->
  <div class="card">
    <div style="padding:14px 18px;border-bottom:1px solid var(--border);font-size:.84rem;font-weight:700;color:var(--dark)">Orders by Status</div>
    <div style="padding:14px 18px">
      @php
        $statusColors = ['pending'=>'#f59e0b','confirmed'=>'#3b82f6','processing'=>'#8b5cf6','shipped'=>'#10b981','delivered'=>'#166534','cancelled'=>'#9ca3af'];
        $totalOs = $ordersByStatus->sum() ?: 1;
      @endphp
      @foreach($statusColors as $s => $c)
      @php $cnt = $ordersByStatus[$s] ?? 0; @endphp
      <div style="display:flex;align-items:center;justify-content:space-between;font-size:.78rem;margin-bottom:9px">
        <div style="display:flex;align-items:center;gap:7px">
          <div style="width:8px;height:8px;border-radius:50%;background:{{ $c }}"></div>
          <span style="color:var(--dark)">{{ ucfirst($s) }}</span>
        </div>
        <div style="display:flex;align-items:center;gap:8px">
          <div style="width:70px;height:5px;border-radius:3px;background:var(--sand);overflow:hidden">
            <div style="width:{{ round($cnt/$totalOs*100) }}%;height:100%;background:{{ $c }};border-radius:3px"></div>
          </div>
          <strong style="color:var(--dark);min-width:18px;text-align:right">{{ $cnt }}</strong>
        </div>
      </div>
      @endforeach
    </div>
  </div>

  <!-- Booking Status -->
  <div class="card">
    <div style="padding:14px 18px;border-bottom:1px solid var(--border);font-size:.84rem;font-weight:700;color:var(--dark)">Bookings Summary</div>
    <div style="padding:14px 18px">
      @php
        $bkColors = ['pending'=>'#f59e0b','confirmed'=>'#10b981','completed'=>'#166534','declined'=>'#ef4444','cancelled'=>'#9ca3af'];
        $totalBk = $bookingsSummary->sum() ?: 1;
      @endphp
      @foreach($bkColors as $s => $c)
      @php $cnt = $bookingsSummary[$s] ?? 0; @endphp
      <div style="display:flex;align-items:center;justify-content:space-between;font-size:.78rem;margin-bottom:9px">
        <div style="display:flex;align-items:center;gap:7px">
          <div style="width:8px;height:8px;border-radius:50%;background:{{ $c }}"></div>
          <span style="color:var(--dark)">{{ ucfirst($s) }}</span>
        </div>
        <strong style="color:var(--dark)">{{ $cnt }}</strong>
      </div>
      @endforeach
    </div>
  </div>

  <!-- Workshop Enrollments -->
  <div class="card">
    <div style="padding:14px 18px;border-bottom:1px solid var(--border);font-size:.84rem;font-weight:700;color:var(--dark)">Workshop Enrollments</div>
    <div style="padding:14px 18px">
      @php
        $wsColors = ['pending'=>'#f59e0b','confirmed'=>'#10b981','attended'=>'#3b82f6','cancelled'=>'#9ca3af'];
        $totalWs = $workshopSummary->sum() ?: 1;
      @endphp
      @foreach($wsColors as $s => $c)
      @php $cnt = $workshopSummary[$s] ?? 0; @endphp
      <div style="display:flex;align-items:center;justify-content:space-between;font-size:.78rem;margin-bottom:9px">
        <div style="display:flex;align-items:center;gap:7px">
          <div style="width:8px;height:8px;border-radius:50%;background:{{ $c }}"></div>
          <span style="color:var(--dark)">{{ ucfirst($s) }}</span>
        </div>
        <strong style="color:var(--dark)">{{ $cnt }}</strong>
      </div>
      @endforeach
    </div>
  </div>
</div>

<!-- Inventory Log -->
@if($inventoryLog->isNotEmpty())
<div class="card">
  <div style="padding:14px 18px;border-bottom:1px solid var(--border);font-size:.84rem;font-weight:700;color:var(--dark)">Recent Inventory Changes</div>
  <div class="tbl-wrap">
    <table class="tbl">
      <thead><tr><th>Product</th><th>Change</th><th>Before</th><th>After</th><th>Reason</th><th>By</th><th>Date</th></tr></thead>
      <tbody>
      @foreach($inventoryLog as $log)
      <tr>
        <td style="font-size:.8rem;font-weight:600;color:var(--dark)">{{ $log->product?->prod_name ?? '—' }}</td>
        <td>
          <span style="font-size:.8rem;font-weight:700;color:{{ $log->qty_changed > 0 ? '#166534' : '#991b1b' }}">
            {{ $log->qty_changed > 0 ? '+' : '' }}{{ $log->qty_changed }}
          </span>
        </td>
        <td style="font-size:.78rem;color:var(--gray)">{{ $log->qty_before }}</td>
        <td style="font-size:.78rem;font-weight:600;color:var(--dark)">{{ $log->qty_after }}</td>
        <td style="font-size:.75rem;color:var(--gray)">{{ Str::limit($log->reason, 35) }}</td>
        <td style="font-size:.75rem;color:var(--gray)">{{ $log->admin?->username ?? '—' }}</td>
        <td style="font-size:.72rem;color:var(--gray)">{{ $log->recorded_at?->format('M j, Y') }}</td>
      </tr>
      @endforeach
      </tbody>
    </table>
  </div>
</div>
@endif
@endsection
