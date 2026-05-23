@extends('layouts.admin')
@section('title','Schedule Slots')
@section('page-title','Consultation Schedule Slots')

@section('content')
@if(session('success'))
<div class="alert alert-success">{{ session('success') }}</div>
@endif
@if(session('error'))
<div class="alert alert-error">{{ session('error') }}</div>
@endif
<div style="display:grid;grid-template-columns:1fr 340px;gap:20px;align-items:start">

  <!-- SLOT LIST -->
  <div>
    <!-- Filters -->
    <div class="card" style="margin-bottom:18px">
      <div class="card-body" style="padding:14px 20px">
        <form method="GET" style="display:flex;gap:10px;align-items:center;flex-wrap:wrap">
          <input class="form-input" type="date" name="date" value="{{ request('date') }}" style="min-width:160px">
          <select class="form-select" name="status">
            <option value="">All Statuses</option>
            <option value="available" {{ request('status')==='available'?'selected':'' }}>Available</option>
            <option value="booked"    {{ request('status')==='booked'?'selected':'' }}>Booked</option>
            <option value="cancelled" {{ request('status')==='cancelled'?'selected':'' }}>Cancelled</option>
          </select>
          <button type="submit" class="btn btn-primary">Filter</button>
          @if(request()->hasAny(['date','status']))
            <a href="{{ route('admin.schedules.index') }}" class="btn btn-outline">Clear</a>
          @endif
        </form>
      </div>
    </div>

    <div class="card">
      <div class="card-header">
        <div class="card-title">{{ $schedules->total() }} Slot{{ $schedules->total()!==1?'s':'' }}</div>
        <a href="{{ route('admin.schedules.index') }}" class="btn btn-outline btn-sm">Refresh</a>
      </div>

      @if($schedules->isEmpty())
        <div style="text-align:center;padding:40px 24px;color:var(--gray);font-size:.84rem">
          No slots found. Create some using the form on the right.
        </div>
      @else
      <div style="overflow-x:auto">
        <table class="tbl">
          <thead>
            <tr>
              <th>Date</th>
              <th>Time</th>
              <th>Type</th>
              <th>Status</th>
              <th>Created By</th>
              <th></th>
            </tr>
          </thead>
          <tbody>
            @foreach($schedules as $slot)
            <tr>
              <td style="font-weight:600;white-space:nowrap">{{ $slot->date->format('D, M d Y') }}</td>
              <td style="white-space:nowrap;font-family:'DM Mono',monospace;font-size:.8rem">{{ $slot->time_range }}</td>
              <td style="font-size:.78rem">{{ $slot->type_label }}</td>
              <td>
                <span class="badge {{ $slot->status==='available'?'badge-confirmed':($slot->status==='booked'?'badge-pending':'badge-cancelled') }}">
                  {{ ucfirst($slot->status) }}
                </span>
              </td>
              <td style="font-size:.78rem;color:var(--gray)">{{ $slot->creator?->full_name ?? '—' }}</td>
              <td>
                @if($slot->status !== 'booked')
                <form method="POST" action="{{ route('admin.schedules.destroy', $slot) }}" onsubmit="return confirm('Delete this slot?')">
                  @csrf @method('DELETE')
                  <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                </form>
                @else
                <a href="{{ route('admin.bookings.index') }}" class="btn btn-outline btn-sm">View Booking</a>
                @endif
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
      @if($schedules->hasPages())
      <div style="padding:14px 20px;border-top:1px solid var(--border)">
        {{ $schedules->withQueryString()->links() }}
      </div>
      @endif
      @endif
    </div>
  </div>

  <!-- CREATE SLOTS -->
  <div>
    <div class="card">
      <div class="card-header"><div class="card-title">Create Available Slots</div></div>
      <div class="card-body">
        <form method="POST" action="{{ route('admin.schedules.store') }}">
          @csrf
          @if($errors->any())
            <div class="alert alert-error" style="margin-bottom:14px">
              @foreach($errors->all() as $e)<div>{{ $e }}</div>@endforeach
            </div>
          @endif

          <div style="margin-bottom:12px">
            <label style="font-size:.75rem;font-weight:600;color:var(--charcoal);display:block;margin-bottom:5px">Date <span style="color:var(--red)">*</span></label>
            <input type="date" name="date" class="form-input" style="width:100%" value="{{ old('date') }}" required min="{{ now()->addDay()->toDateString() }}">
          </div>

          <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-bottom:12px">
            <div>
              <label style="font-size:.75rem;font-weight:600;color:var(--charcoal);display:block;margin-bottom:5px">Start Time <span style="color:var(--red)">*</span></label>
              <input type="time" name="start_time" class="form-input" style="width:100%" value="{{ old('start_time','09:00') }}" required>
            </div>
            <div>
              <label style="font-size:.75rem;font-weight:600;color:var(--charcoal);display:block;margin-bottom:5px">End Time <span style="color:var(--red)">*</span></label>
              <input type="time" name="end_time" class="form-input" style="width:100%" value="{{ old('end_time','10:00') }}" required>
            </div>
          </div>

          <div style="margin-bottom:12px">
            <label style="font-size:.75rem;font-weight:600;color:var(--charcoal);display:block;margin-bottom:5px">Consultation Type <span style="color:var(--red)">*</span></label>
            <select name="type" class="form-select" style="width:100%">
              <option value="any"                  {{ old('type')==='any'?'selected':'' }}>Any (Open to all types)</option>
              <option value="research_consultancy" {{ old('type')==='research_consultancy'?'selected':'' }}>Research Consultancy</option>
              <option value="product_development"  {{ old('type')==='product_development'?'selected':'' }}>Product Development</option>
              <option value="general_consultancy"  {{ old('type')==='general_consultancy'?'selected':'' }}>General Consultancy</option>
            </select>
          </div>

          <div style="margin-bottom:16px">
            <label style="font-size:.75rem;font-weight:600;color:var(--charcoal);display:block;margin-bottom:5px">Number of Slots to Create</label>
            <input type="number" name="slots" class="form-input" style="width:100%" value="{{ old('slots',1) }}" min="1" max="10">
            <div style="font-size:.7rem;color:var(--gray);margin-top:4px">Creates multiple identical slots at the same time block.</div>
          </div>

          <button type="submit" class="btn btn-primary" style="width:100%" id="createSlotBtn"
            onclick="this.disabled=true; this.textContent='Creating…'; this.form.submit();">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            Create Slot(s)
          </button>
        </form>
      </div>
    </div>

    <!-- Quick reference -->
    <div class="card" style="margin-top:16px">
      <div class="card-header"><div class="card-title">Recommended Schedule</div></div>
      <div class="card-body" style="padding:14px 18px">
        <div style="font-size:.78rem;color:var(--charcoal);line-height:1.8">
          <div style="font-weight:600;color:var(--dark);margin-bottom:6px">Standard consultation hours:</div>
          <div>🕘 09:00 – 09:00 AM session</div>
          <div>🕙 10:00 – 11:00 AM session</div>
          <div>🕐 13:00 – 14:00 PM session</div>
          <div>🕑 14:00 – 15:00 PM session</div>
          <div>🕒 15:00 – 16:00 PM session</div>
          <div style="margin-top:8px;font-size:.72rem;color:var(--gray)">Weekdays only (Mon–Fri). Slots must be created at least 1 day in advance.</div>
        </div>
      </div>
    </div>
  </div>

</div>
@endsection
