@extends('layouts.admin')
@section('title','Workshops')

@section('content')
<div class="page-header">
  <div>
    <h1 class="page-title">Workshops &amp; Training</h1>
    <p class="page-sub">Manage training sessions and participant enrollment.</p>
  </div>
  <a href="{{ route('admin.workshops.create') }}" class="btn btn-primary">
    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
    New Workshop
  </a>
</div>

@if(session('success'))
<div class="alert alert-success">{{ session('success') }}</div>
@endif
@if(session('error'))
<div class="alert alert-error">{{ session('error') }}</div>
@endif

<!-- Filters -->
<div class="card" style="margin-bottom:18px">
  <form method="GET" action="{{ route('admin.workshops.index') }}" style="display:flex;gap:10px;align-items:flex-end;flex-wrap:wrap;padding:16px 20px">
    <div style="flex:1;min-width:200px">
      <div style="font-size:.73rem;font-weight:700;color:var(--dark);margin-bottom:5px">Search</div>
      <input class="form-control" type="text" name="search" placeholder="Title or venue..." value="{{ request('search') }}">
    </div>
    <div style="min-width:130px">
      <div style="font-size:.73rem;font-weight:700;color:var(--dark);margin-bottom:5px">Status</div>
      <select class="form-control form-select" name="status">
        <option value="">All</option>
        <option value="open" {{ request('status')==='open'?'selected':'' }}>Open</option>
        <option value="coming_soon" {{ request('status')==='coming_soon'?'selected':'' }}>Coming Soon</option>
        <option value="ongoing" {{ request('status')==='ongoing'?'selected':'' }}>Ongoing</option>
        <option value="completed" {{ request('status')==='completed'?'selected':'' }}>Completed</option>
        <option value="cancelled" {{ request('status')==='cancelled'?'selected':'' }}>Cancelled</option>
      </select>
    </div>
    <button type="submit" class="btn btn-primary" style="height:38px">Filter</button>
    @if(request()->hasAny(['search','status']))
      <a href="{{ route('admin.workshops.index') }}" class="btn btn-secondary" style="height:38px">Clear</a>
    @endif
  </form>
</div>

<div class="card">
  <div class="tbl-wrap">
    <table class="tbl">
      <thead>
        <tr>
          <th>Title</th>
          <th>Date &amp; Time</th>
          <th>Venue</th>
          <th>Fee</th>
          <th>Slots</th>
          <th>Status</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        @forelse($sessions as $session)
        @php
          $statusClass = match($session->status){
            'ongoing'   => 'badge-info',
            'completed' => 'badge-success',
            'cancelled' => 'badge-danger',
            default     => 'badge-warning',
          };
          $filled = $session->confirmed_count ?? 0;
          $pct    = $session->max_participants > 0 ? round($filled / $session->max_participants * 100) : 0;
        @endphp
        <tr>
          <td>
            <div style="font-size:.82rem;font-weight:700;color:var(--dark)">{{ $session->title }}</div>
            <div style="font-size:.7rem;color:var(--gray)">{{ $session->registrations_count }} enrolled</div>
          </td>
          <td style="font-size:.78rem;color:var(--gray);white-space:nowrap">{{ $session->session_datetime->format('M j, Y · g:i A') }}</td>
          <td style="font-size:.78rem;color:var(--gray)">{{ Str::limit($session->venue, 30) }}</td>
          <td style="font-size:.82rem;font-weight:600;color:var(--dark)">
            @if((float)$session->fee === 0.0) <span style="color:var(--emerald)">Free</span>
            @else ₱{{ number_format($session->fee, 2) }}
            @endif
          </td>
          <td>
            <div style="font-size:.78rem;color:var(--dark);font-weight:600;margin-bottom:3px">{{ $filled }}/{{ $session->max_participants }}</div>
            @php $barColor = $pct >= 100 ? '#dc2626' : ($pct >= 75 ? '#f59e0b' : 'var(--forest)'); @endphp
            <div style="width:60px;height:4px;border-radius:2px;background:var(--border);overflow:hidden">
              <div style="width:{{ $pct }}%;height:100%;background:{{ $barColor }};border-radius:2px"></div>
            </div>
          </td>
          <td><span class="badge {{ $statusClass }}">{{ ucfirst(str_replace('_',' ',$session->status)) }}</span></td>
          <td>
            <div style="display:flex;gap:6px">
              <a href="{{ route('admin.workshops.show', $session) }}" class="btn btn-secondary btn-sm">View</a>
              @if($session->status !== 'cancelled')
              <form method="POST" action="{{ route('admin.workshops.destroy', $session) }}" onsubmit="return confirm('Cancel this workshop?')">
                @csrf @method('DELETE')
                <button type="submit" class="btn btn-danger btn-sm">Cancel</button>
              </form>
              @endif
            </div>
          </td>
        </tr>
        @empty
        <tr><td colspan="7" style="text-align:center;padding:40px;color:var(--gray)">No workshops found.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
  @if($sessions->hasPages())
  <div style="padding:16px 20px;border-top:1px solid var(--border)">{{ $sessions->withQueryString()->links() }}</div>
  @endif
</div>
@endsection
