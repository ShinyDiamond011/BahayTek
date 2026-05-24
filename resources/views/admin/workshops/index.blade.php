@extends('layouts.admin')
@section('title','Workshops')

@section('content')
<div class="page-header">
  <div>
    <h1 class="page-title">Workshops &amp; Training</h1>
    <p class="page-sub">Manage training sessions and participant enrollment.</p>
  </div>
  <button class="btn btn-primary" onclick="document.getElementById('modalNewWorkshop').style.display='flex'">
    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
    New Workshop
  </button>
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
              <form method="POST" action="{{ route('admin.workshops.destroy', $session) }}" onsubmit="return confirm('Cancel this workshop? Participants will no longer be able to enroll.')">
                @csrf @method('DELETE')
                <button type="submit" class="btn btn-danger btn-sm">Cancel</button>
              </form>
              @endif
              <form method="POST" action="{{ route('admin.workshops.delete', $session) }}" onsubmit="return confirm('Permanently delete \"{{ addslashes($session->title) }}\"? This cannot be undone and will remove all registrations.')">
                @csrf @method('DELETE')
                <button type="submit" class="btn btn-sm" style="background:#7f1d1d;color:#fff;border-color:#7f1d1d">Delete</button>
              </form>
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

@push('scripts')
{{-- New Workshop Modal --}}
<div id="modalNewWorkshop" style="display:none;position:fixed;inset:0;z-index:1000;align-items:center;justify-content:center;background:rgba(0,0,0,.45);padding:20px">
  <div style="background:#fff;border-radius:16px;width:100%;max-width:640px;max-height:90vh;overflow-y:auto;box-shadow:0 24px 64px rgba(0,0,0,.2)">
    <div style="padding:20px 24px;border-bottom:1px solid var(--border);display:flex;align-items:center;justify-content:space-between">
      <div style="font-size:1rem;font-weight:700;color:var(--dark)">New Workshop</div>
      <button onclick="document.getElementById('modalNewWorkshop').style.display='none'" style="background:none;border:none;cursor:pointer;color:var(--gray);font-size:1.3rem;line-height:1;padding:4px">&times;</button>
    </div>
    <form method="POST" action="{{ route('admin.workshops.store') }}" style="padding:24px">
      @csrf

      <div style="margin-bottom:14px">
        <label style="font-size:.73rem;font-weight:700;color:var(--dark);display:block;margin-bottom:5px">Workshop Title *</label>
        <input class="form-control @error('title') error @enderror" type="text" name="title" value="{{ old('title') }}" required placeholder="e.g., Solar Panel Installation Basics">
        @error('title')<div style="font-size:.7rem;color:#dc2626;margin-top:3px">{{ $message }}</div>@enderror
      </div>

      <div style="margin-bottom:14px">
        <label style="font-size:.73rem;font-weight:700;color:var(--dark);display:block;margin-bottom:5px">Description</label>
        <textarea class="form-control" name="description" rows="3" placeholder="Topics covered, requirements, what participants will learn...">{{ old('description') }}</textarea>
      </div>

      <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:14px">
        <div>
          <label style="font-size:.73rem;font-weight:700;color:var(--dark);display:block;margin-bottom:5px">Date &amp; Time *</label>
          <input class="form-control @error('session_datetime') error @enderror" type="datetime-local" name="session_datetime" value="{{ old('session_datetime') }}" required>
          @error('session_datetime')<div style="font-size:.7rem;color:#dc2626;margin-top:3px">{{ $message }}</div>@enderror
        </div>
        <div>
          <label style="font-size:.73rem;font-weight:700;color:var(--dark);display:block;margin-bottom:5px">Max Participants *</label>
          <input class="form-control @error('max_participants') error @enderror" type="number" name="max_participants" value="{{ old('max_participants', 20) }}" min="1" required>
          @error('max_participants')<div style="font-size:.7rem;color:#dc2626;margin-top:3px">{{ $message }}</div>@enderror
        </div>
      </div>

      <div style="margin-bottom:14px">
        <label style="font-size:.73rem;font-weight:700;color:var(--dark);display:block;margin-bottom:5px">Registration Deadline <span style="font-weight:400;color:var(--gray)">(optional)</span></label>
        <input class="form-control @error('registration_deadline') error @enderror" type="datetime-local" name="registration_deadline" value="{{ old('registration_deadline') }}">
        <div style="font-size:.68rem;color:var(--gray);margin-top:2px">Leave blank for no deadline.</div>
        @error('registration_deadline')<div style="font-size:.7rem;color:#dc2626;margin-top:3px">{{ $message }}</div>@enderror
      </div>

      <div style="margin-bottom:14px">
        <label style="font-size:.73rem;font-weight:700;color:var(--dark);display:block;margin-bottom:5px">Venue *</label>
        <input class="form-control @error('venue') error @enderror" type="text" name="venue" value="{{ old('venue') }}" required placeholder="e.g., BAHAYTEK Training Center, Legazpi City">
        @error('venue')<div style="font-size:.7rem;color:#dc2626;margin-top:3px">{{ $message }}</div>@enderror
      </div>

      <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:20px">
        <div>
          <label style="font-size:.73rem;font-weight:700;color:var(--dark);display:block;margin-bottom:5px">Fee (₱) *</label>
          <input class="form-control @error('fee') error @enderror" type="number" name="fee" value="{{ old('fee', 0) }}" min="0" step="0.01" required>
          <div style="font-size:.68rem;color:var(--gray);margin-top:2px">Enter 0 for free sessions</div>
          @error('fee')<div style="font-size:.7rem;color:#dc2626;margin-top:3px">{{ $message }}</div>@enderror
        </div>
        <div>
          <label style="font-size:.73rem;font-weight:700;color:var(--dark);display:block;margin-bottom:5px">Status *</label>
          <select class="form-control form-select @error('status') error @enderror" name="status">
            <option value="open" {{ old('status','open')==='open'?'selected':'' }}>Open</option>
            <option value="coming_soon" {{ old('status')==='coming_soon'?'selected':'' }}>Coming Soon</option>
            <option value="ongoing" {{ old('status')==='ongoing'?'selected':'' }}>Ongoing</option>
          </select>
          @error('status')<div style="font-size:.7rem;color:#dc2626;margin-top:3px">{{ $message }}</div>@enderror
        </div>
      </div>

      <div style="display:flex;gap:10px;justify-content:flex-end;border-top:1px solid var(--border);padding-top:16px">
        <button type="button" onclick="document.getElementById('modalNewWorkshop').style.display='none'" class="btn btn-secondary">Cancel</button>
        <button type="submit" class="btn btn-primary">Create Workshop</button>
      </div>
    </form>
  </div>
</div>
<script>
document.getElementById('modalNewWorkshop').addEventListener('click', function(e) {
  if (e.target === this) this.style.display = 'none';
});
@if($errors->any() && old('title'))
document.getElementById('modalNewWorkshop').style.display = 'flex';
@endif
</script>
@endpush
