@extends('layouts.admin')
@section('title','Consultation Bookings')
@section('page-title','Consultation Bookings')

@section('content')
<!-- STAT TABS -->
<div style="display:flex;gap:8px;margin-bottom:22px;flex-wrap:wrap">
  @php
    $statItems = [
      ['label'=>'All',       'key'=>'',          'count'=>$counts['all'],       'color'=>'var(--forest)'],
      ['label'=>'Pending',   'key'=>'pending',   'count'=>$counts['pending'],   'color'=>'#854d0e'],
      ['label'=>'Confirmed', 'key'=>'confirmed', 'count'=>$counts['confirmed'], 'color'=>'#166534'],
      ['label'=>'Declined',  'key'=>'declined',  'count'=>'#dc2626',            'color'=>'#dc2626'],
      ['label'=>'Cancelled', 'key'=>'cancelled', 'count'=>$counts['cancelled'], 'color'=>'#6b7280'],
      ['label'=>'Completed', 'key'=>'completed', 'count'=>$counts['completed'], 'color'=>'var(--forest)'],
    ];
    $activeStatus = request('status','');
  @endphp
  @foreach([
    ['label'=>'All',       'key'=>'',          'count'=>$counts['all']],
    ['label'=>'Pending',   'key'=>'pending',   'count'=>$counts['pending']],
    ['label'=>'Confirmed', 'key'=>'confirmed', 'count'=>$counts['confirmed']],
    ['label'=>'Declined',  'key'=>'declined',  'count'=>$counts['declined']],
    ['label'=>'Cancelled', 'key'=>'cancelled', 'count'=>$counts['cancelled']],
    ['label'=>'Completed', 'key'=>'completed', 'count'=>$counts['completed']],
  ] as $tab)
  <a href="{{ route('admin.bookings.index', array_merge(request()->query(), ['status'=>$tab['key'],'page'=>1])) }}"
     style="padding:7px 16px;border-radius:20px;font-size:.76rem;font-weight:600;text-decoration:none;transition:all .2s;border:1.5px solid var(--border);
            background:{{ $activeStatus===$tab['key'] ? 'var(--forest)' : '#fff' }};
            color:{{ $activeStatus===$tab['key'] ? '#fff' : 'var(--gray)' }};">
    {{ $tab['label'] }} <span style="opacity:.75">({{ $tab['count'] }})</span>
  </a>
  @endforeach
</div>

<!-- FILTERS -->
<div class="card" style="margin-bottom:20px">
  <div class="card-body" style="padding:16px 22px">
    <form method="GET" action="{{ route('admin.bookings.index') }}" style="display:flex;gap:10px;align-items:center;flex-wrap:wrap">
      <input type="hidden" name="status" value="{{ request('status') }}">
      <input class="form-input" type="text" name="search" placeholder="Search name, email, topic..." value="{{ request('search') }}" style="flex:1;min-width:200px">
      <select class="form-select" name="service">
        <option value="">All Services</option>
        <option value="research_consultancy" {{ request('service')==='research_consultancy'?'selected':'' }}>Research Consultancy</option>
        <option value="product_development"  {{ request('service')==='product_development'?'selected':'' }}>Product Development</option>
        <option value="general_consultancy"  {{ request('service')==='general_consultancy'?'selected':'' }}>General Consultancy</option>
      </select>
      <button type="submit" class="btn btn-primary">Filter</button>
      @if(request()->hasAny(['search','service']))
        <a href="{{ route('admin.bookings.index', ['status'=>request('status')]) }}" class="btn btn-outline">Clear</a>
      @endif
    </form>
  </div>
</div>

<!-- TABLE -->
<div class="card">
  <div class="card-header">
    <div class="card-title">
      {{ $bookings->total() }} {{ Str::plural('Booking', $bookings->total()) }}
      @if(request('status')) · {{ ucfirst(request('status')) }} @endif
    </div>
  </div>

  @if($bookings->isEmpty())
    <div style="text-align:center;padding:48px 24px;color:var(--gray);font-size:.85rem">
      No bookings found matching your filters.
    </div>
  @else
  <div style="overflow-x:auto">
    <table class="tbl">
      <thead>
        <tr>
          <th>#</th>
          <th>Client</th>
          <th>Service</th>
          <th>Date</th>
          <th>Time</th>
          <th>Topic</th>
          <th>Status</th>
          <th>Booked</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        @foreach($bookings as $b)
        <tr>
          <td style="font-family:'DM Mono',monospace;font-size:.75rem;color:var(--gray)">#{{ str_pad($b->id,5,'0',STR_PAD_LEFT) }}</td>
          <td>
            <div style="font-weight:600;font-size:.83rem">{{ $b->full_name }}</div>
            <div style="font-size:.73rem;color:var(--gray)">{{ $b->email }}</div>
          </td>
          <td style="font-size:.78rem;white-space:nowrap">{{ $b->schedule?->type_label ?? '—' }}</td>
          <td style="white-space:nowrap;font-size:.82rem">{{ $b->schedule?->date->format('M d, Y') ?? '—' }}</td>
          <td style="white-space:nowrap;font-size:.82rem">{{ $b->schedule?->time_range ?? '—' }}</td>
          <td style="max-width:220px">
            <div style="white-space:nowrap;overflow:hidden;text-overflow:ellipsis;font-size:.8rem;max-width:200px" title="{{ $b->topic }}">{{ $b->topic }}</div>
          </td>
          <td><span class="badge badge-{{ $b->status }}">{{ ucfirst($b->status) }}</span></td>
          <td style="font-size:.78rem;color:var(--gray);white-space:nowrap">{{ $b->created_at->format('M d, Y') }}</td>
          <td><a href="{{ route('admin.bookings.show', $b) }}" class="btn btn-outline btn-sm">View</a></td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>

  @if($bookings->hasPages())
  <div style="padding:14px 22px;border-top:1px solid var(--border)">
    {{ $bookings->withQueryString()->links() }}
  </div>
  @endif
  @endif
</div>
@endsection
