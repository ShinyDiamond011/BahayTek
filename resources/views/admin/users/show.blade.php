@extends('layouts.admin')
@section('title', $user->first_name.' '.$user->last_name)

@section('content')
<div class="page-header">
  <div style="display:flex;align-items:center;gap:12px">
    <a href="{{ route('admin.users.index') }}" style="display:flex;align-items:center;color:var(--gray);text-decoration:none;font-size:.82rem;font-weight:600">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg>
      Users
    </a>
    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="var(--border)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
    <h1 class="page-title" style="margin:0;font-size:1.1rem">{{ $user->first_name }} {{ $user->last_name }}</h1>
  </div>
</div>

@if(session('success'))
<div class="alert alert-success">{{ session('success') }}</div>
@endif

<div style="display:grid;grid-template-columns:1fr 280px;gap:20px;align-items:start">
  <div>
    <!-- Profile card -->
    <div class="card" style="margin-bottom:18px">
      <div style="padding:15px 20px;border-bottom:1px solid var(--border);font-size:.85rem;font-weight:700;color:var(--dark)">Profile Information</div>
      <div style="padding:18px 20px">
        <div style="display:flex;align-items:center;gap:16px;margin-bottom:20px">
          <div style="width:56px;height:56px;border-radius:50%;background:var(--sand);display:flex;align-items:center;justify-content:center;font-size:1.3rem;font-weight:700;color:var(--forest);flex-shrink:0">
            {{ strtoupper(substr($user->first_name,0,1).substr($user->last_name,0,1)) }}
          </div>
          <div>
            <div style="font-size:1rem;font-weight:700;color:var(--dark)">{{ $user->first_name }} {{ $user->last_name }}</div>
            <div style="font-size:.8rem;color:var(--gray)">Registered {{ $user->created_at->format('F j, Y') }}</div>
          </div>
        </div>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;font-size:.82rem">
          @foreach([
            'Email'        => $user->email,
            'Phone'        => $user->phone ?? '—',
            'Address'      => $user->address ?? '—',
            'Municipality' => $user->municipality ?? '—',
            'Province'     => $user->province ?? '—',
          ] as $lbl => $val)
          <div>
            <div style="font-size:.68rem;font-weight:700;color:var(--gray);letter-spacing:.3px;text-transform:uppercase;margin-bottom:2px">{{ $lbl }}</div>
            <div style="color:var(--dark)">{{ $val }}</div>
          </div>
          @endforeach
        </div>
      </div>
    </div>

    <!-- Orders -->
    @if($user->orders->isNotEmpty())
    <div class="card" style="margin-bottom:18px">
      <div style="padding:15px 20px;border-bottom:1px solid var(--border);font-size:.85rem;font-weight:700;color:var(--dark)">
        Recent Orders ({{ $user->orders->count() }})
      </div>
      <div class="tbl-wrap">
        <table class="tbl">
          <thead><tr><th>Order</th><th>Status</th><th>Payment</th><th>Total</th><th>Date</th><th></th></tr></thead>
          <tbody>
          @foreach($user->orders as $order)
          @php
            $sc = match($order->status){
              'confirmed'=>'badge-info','processing'=>'badge-warning',
              'shipped'=>'badge-success','delivered'=>'badge-success',
              'cancelled'=>'badge-danger',default=>'badge-secondary'
            };
            $pc = match($order->payment_status){
              'paid'=>'badge-success','refunded'=>'badge-secondary',default=>'badge-warning'
            };
          @endphp
          <tr>
            <td style="font-size:.82rem;font-weight:700;color:var(--dark)">#{{ str_pad($order->id,5,'0',STR_PAD_LEFT) }}</td>
            <td><span class="badge {{ $sc }}" style="font-size:.58rem">{{ ucfirst($order->status) }}</span></td>
            <td><span class="badge {{ $pc }}" style="font-size:.58rem">{{ ucfirst($order->payment_status) }}</span></td>
            <td style="font-size:.82rem;font-weight:600;color:var(--dark)">₱{{ number_format($order->total_amount,2) }}</td>
            <td style="font-size:.75rem;color:var(--gray)">{{ $order->ordered_at->format('M j, Y') }}</td>
            <td><a href="{{ route('admin.orders.show', $order) }}" class="btn btn-secondary btn-sm">View</a></td>
          </tr>
          @endforeach
          </tbody>
        </table>
      </div>
    </div>
    @endif

    <!-- Bookings -->
    @if($user->bookingSchedules->isNotEmpty())
    <div class="card">
      <div style="padding:15px 20px;border-bottom:1px solid var(--border);font-size:.85rem;font-weight:700;color:var(--dark)">Recent Consultation Bookings</div>
      <div class="tbl-wrap">
        <table class="tbl">
          <thead><tr><th>Date</th><th>Time</th><th>Type</th><th>Status</th><th></th></tr></thead>
          <tbody>
          @foreach($user->bookingSchedules as $bk)
          @php
            $bc = match($bk->status){
              'confirmed'=>'badge-confirmed','completed'=>'badge-completed',
              'declined'=>'badge-declined','cancelled'=>'badge-cancelled',
              default=>'badge-pending'
            };
          @endphp
          <tr>
            <td style="font-size:.8rem;color:var(--dark)">{{ $bk->schedule?->date?->format('M j, Y') ?? '—' }}</td>
            <td style="font-size:.78rem;color:var(--gray)">{{ $bk->schedule?->time_range ?? '—' }}</td>
            <td style="font-size:.78rem;color:var(--gray)">{{ $bk->schedule?->type_label ?? '—' }}</td>
            <td><span class="badge {{ $bc }}">{{ ucfirst($bk->status) }}</span></td>
            <td><a href="{{ route('admin.bookings.show', $bk) }}" class="btn btn-secondary btn-sm">View</a></td>
          </tr>
          @endforeach
          </tbody>
        </table>
      </div>
    </div>
    @endif
  </div>

  <!-- Sidebar -->
  <div>
    <div class="card" style="padding:18px">
      <div style="font-size:.82rem;font-weight:700;color:var(--dark);margin-bottom:14px;padding-bottom:12px;border-bottom:1px solid var(--border)">Account Status</div>
      @php
        $sc = match($user->status){
          'inactive'  => 'badge-secondary',
          'suspended' => 'badge-danger',
          default     => 'badge-success',
        };
      @endphp
      <span class="badge {{ $sc }}" style="margin-bottom:16px;display:inline-block">{{ ucfirst($user->status) }}</span>

      <form method="POST" action="{{ route('admin.users.status', $user) }}">
        @csrf @method('PATCH')
        <div style="margin-bottom:10px">
          <label style="font-size:.73rem;font-weight:700;color:var(--dark);display:block;margin-bottom:5px">Change Status</label>
          <select name="status" class="form-control form-select" style="margin-bottom:10px">
            @foreach(['active','inactive','suspended'] as $s)
            <option value="{{ $s }}" {{ $user->status === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
            @endforeach
          </select>
        </div>
        <button type="submit" class="btn btn-primary" style="width:100%">Update Status</button>
      </form>
    </div>

    <div class="card" style="margin-top:14px;padding:18px">
      <div style="font-size:.82rem;font-weight:700;color:var(--dark);margin-bottom:12px;padding-bottom:10px;border-bottom:1px solid var(--border)">Summary</div>
      @foreach([
        'Total Orders'  => $user->orders->count(),
        'Total Spent'   => '₱'.number_format($user->orders->sum('total_amount'),2),
        'Bookings'      => $user->bookingSchedules->count(),
      ] as $lbl => $val)
      <div style="display:flex;justify-content:space-between;font-size:.8rem;margin-bottom:8px">
        <span style="color:var(--gray)">{{ $lbl }}</span>
        <strong style="color:var(--dark)">{{ $val }}</strong>
      </div>
      @endforeach
    </div>
  </div>
</div>
@endsection
