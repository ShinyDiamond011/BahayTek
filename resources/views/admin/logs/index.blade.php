@extends('layouts.admin')
@section('title','Activity Logs')

@section('content')
<div class="page-header">
  <div>
    <h1 class="page-title">Activity Logs</h1>
    <p class="page-sub">System-wide activity trail — orders, bookings, enrollments, and more.</p>
  </div>
</div>

@if(session('success'))
<div class="alert alert-success">{{ session('success') }}</div>
@endif

{{-- Filters --}}
<div class="card" style="margin-bottom:18px">
  <form method="GET" action="{{ route('admin.logs.index') }}" style="display:flex;gap:10px;align-items:flex-end;flex-wrap:wrap;padding:16px 20px">
    <div style="flex:1;min-width:200px">
      <div style="font-size:.73rem;font-weight:700;color:var(--dark);margin-bottom:5px">Search</div>
      <input class="form-input" type="text" name="search" placeholder="Description or IP..." value="{{ request('search') }}" style="width:100%">
    </div>
    <div style="min-width:160px">
      <div style="font-size:.73rem;font-weight:700;color:var(--dark);margin-bottom:5px">Event Type</div>
      <select class="form-select" name="type" style="width:100%">
        <option value="">All Types</option>
        @foreach($types as $t)
        <option value="{{ $t }}" {{ request('type')===$t?'selected':'' }}>{{ ucwords(str_replace('_',' ',$t)) }}</option>
        @endforeach
      </select>
    </div>
    <div style="min-width:140px">
      <div style="font-size:.73rem;font-weight:700;color:var(--dark);margin-bottom:5px">Date</div>
      <input class="form-input" type="date" name="date" value="{{ request('date') }}" style="width:100%">
    </div>
    <button type="submit" class="btn btn-primary" style="height:38px">Filter</button>
    @if(request()->hasAny(['search','type','date']))
      <a href="{{ route('admin.logs.index') }}" class="btn btn-secondary" style="height:38px">Clear</a>
    @endif
  </form>
</div>

<div class="card">
  <div class="card-header">
    <div class="card-title">{{ $logs->total() }} log entr{{ $logs->total() !== 1 ? 'ies' : 'y' }}</div>
  </div>
  <div class="tbl-wrap">
    <table class="tbl">
      <thead>
        <tr>
          <th>Time</th>
          <th>Event</th>
          <th>Description</th>
          <th>User / Staff</th>
          <th>IP Address</th>
          <th>Metadata</th>
        </tr>
      </thead>
      <tbody>
        @forelse($logs as $log)
        @php
          $typeColors = [
            'order_placed'          => '#166534',
            'workshop_enrollment'   => '#1e40af',
            'workshop_cancellation' => '#6b7280',
            'consultation_booked'   => '#7c3aed',
          ];
          $tc = $typeColors[$log->type] ?? '#374151';
        @endphp
        <tr>
          <td style="font-size:.75rem;color:var(--gray);white-space:nowrap">
            {{ $log->logged_at?->format('M j, Y') }}<br>
            <span style="font-family:'DM Mono',monospace;font-size:.7rem">{{ $log->logged_at?->format('H:i:s') }}</span>
          </td>
          <td>
            <span style="display:inline-block;padding:2px 8px;border-radius:12px;font-size:.62rem;font-weight:700;letter-spacing:.4px;text-transform:uppercase;color:#fff;background:{{ $tc }}">
              {{ str_replace('_',' ',$log->type) }}
            </span>
          </td>
          <td style="font-size:.8rem;color:var(--dark);max-width:280px">{{ $log->description }}</td>
          <td style="font-size:.78rem">
            @if($log->user)
              <div style="font-weight:600;color:var(--dark)">{{ $log->user->first_name }} {{ $log->user->last_name }}</div>
              <div style="color:var(--gray);font-size:.7rem">{{ $log->user->email }}</div>
            @elseif($log->staff)
              <div style="font-weight:600;color:var(--dark)">{{ $log->staff->username }}</div>
              <div style="color:var(--gray);font-size:.7rem">Staff</div>
            @else
              <span style="color:var(--gray)">—</span>
            @endif
          </td>
          <td style="font-size:.75rem;color:var(--gray);font-family:'DM Mono',monospace">{{ $log->ip_address ?? '—' }}</td>
          <td style="font-size:.7rem;color:var(--gray);max-width:160px">
            @if($log->metadata)
              @foreach($log->metadata as $k => $v)
                <div><strong>{{ $k }}:</strong> {{ is_array($v) ? json_encode($v) : $v }}</div>
              @endforeach
            @else —
            @endif
          </td>
        </tr>
        @empty
        <tr><td colspan="6" style="text-align:center;padding:40px;color:var(--gray)">No activity logs yet.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
  @if($logs->hasPages())
  <div style="padding:16px 20px;border-top:1px solid var(--border)">
    {{ $logs->withQueryString()->links() }}
  </div>
  @endif
</div>
@endsection
