@extends('layouts.admin')
@section('title', $session->title)

@section('content')
<div class="page-header">
  <div style="display:flex;align-items:center;gap:12px">
    <a href="{{ route('admin.workshops.index') }}" style="display:flex;align-items:center;color:var(--gray);text-decoration:none;font-size:.82rem;font-weight:600">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg>
      Workshops
    </a>
    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="var(--border)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
    <h1 class="page-title" style="margin:0;font-size:1.05rem">{{ Str::limit($session->title, 50) }}</h1>
  </div>
  @php
    $sc = match($session->status){
      'ongoing'   => 'badge-info',
      'completed' => 'badge-success',
      'cancelled' => 'badge-danger',
      default     => 'badge-warning',
    };
  @endphp
  <span class="badge {{ $sc }}" style="font-size:.72rem;padding:5px 14px">{{ ucfirst(str_replace('_',' ',$session->status)) }}</span>
</div>

@if(session('success'))
<div class="alert alert-success">{{ session('success') }}</div>
@endif

<div style="display:grid;grid-template-columns:1fr 300px;gap:20px;align-items:start">
  <div>
    <!-- Session details card -->
    <div class="card" style="margin-bottom:18px">
      <div style="padding:15px 20px;border-bottom:1px solid var(--border);font-size:.85rem;font-weight:700;color:var(--dark)">Session Details</div>
      <div style="padding:20px">
        @if($session->description)
          <p style="font-size:.84rem;color:var(--gray);line-height:1.6;margin-bottom:16px">{{ $session->description }}</p>
        @endif
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;font-size:.82rem">
          @foreach([
            'Date &amp; Time'    => $session->session_datetime->format('F j, Y · g:i A'),
            'Venue'          => $session->venue,
            'Fee'            => (float)$session->fee === 0.0 ? 'Free' : '₱'.number_format($session->fee,2),
            'Max Slots'      => $session->max_participants,
            'Created by'     => $session->creator?->username ?? '—',
          ] as $lbl => $val)
          <div>
            <div style="font-size:.68rem;font-weight:700;color:var(--gray);letter-spacing:.3px;text-transform:uppercase;margin-bottom:2px">{!! $lbl !!}</div>
            <div style="color:var(--dark);font-weight:600">{{ $val }}</div>
          </div>
          @endforeach
        </div>
      </div>
    </div>

    <!-- Edit form -->
    <div class="card" style="margin-bottom:18px">
      <div style="padding:15px 20px;border-bottom:1px solid var(--border);font-size:.85rem;font-weight:700;color:var(--dark)">Edit Workshop</div>
      <form method="POST" action="{{ route('admin.workshops.update', $session) }}" style="padding:20px">
        @csrf @method('PUT')
        <div style="margin-bottom:14px">
          <label style="font-size:.73rem;font-weight:700;color:var(--dark);display:block;margin-bottom:5px">Title</label>
          <input class="form-control" type="text" name="title" value="{{ $session->title }}" required>
        </div>
        <div style="margin-bottom:14px">
          <label style="font-size:.73rem;font-weight:700;color:var(--dark);display:block;margin-bottom:5px">Description</label>
          <textarea class="form-control" name="description" rows="3">{{ $session->description }}</textarea>
        </div>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:14px">
          <div>
            <label style="font-size:.73rem;font-weight:700;color:var(--dark);display:block;margin-bottom:5px">Date &amp; Time</label>
            <input class="form-control" type="datetime-local" name="session_datetime" value="{{ $session->session_datetime->format('Y-m-d\TH:i') }}" required>
          </div>
          <div>
            <label style="font-size:.73rem;font-weight:700;color:var(--dark);display:block;margin-bottom:5px">Max Participants</label>
            <input class="form-control" type="number" name="max_participants" value="{{ $session->max_participants }}" min="1" required>
          </div>
        </div>
        <div style="margin-bottom:14px">
          <label style="font-size:.73rem;font-weight:700;color:var(--dark);display:block;margin-bottom:5px">Venue</label>
          <input class="form-control" type="text" name="venue" value="{{ $session->venue }}" required>
        </div>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:18px">
          <div>
            <label style="font-size:.73rem;font-weight:700;color:var(--dark);display:block;margin-bottom:5px">Fee (₱)</label>
            <input class="form-control" type="number" name="fee" value="{{ $session->fee }}" min="0" step="0.01">
          </div>
          <div>
            <label style="font-size:.73rem;font-weight:700;color:var(--dark);display:block;margin-bottom:5px">Status</label>
            <select class="form-control form-select" name="status">
              @foreach(['open','coming_soon','ongoing','completed','cancelled'] as $s)
              <option value="{{ $s }}" {{ $session->status === $s ? 'selected' : '' }}>{{ ucfirst(str_replace('_',' ',$s)) }}</option>
              @endforeach
            </select>
          </div>
        </div>
        <div style="display:flex;justify-content:flex-end">
          <button type="submit" class="btn btn-primary">Save Changes</button>
        </div>
      </form>
    </div>

    <!-- Registrations -->
    <div class="card">
      <div style="padding:15px 20px;border-bottom:1px solid var(--border);font-size:.85rem;font-weight:700;color:var(--dark)">
        Registrations ({{ $registrations->total() }})
      </div>
      <div class="tbl-wrap">
        <table class="tbl">
          <thead>
            <tr>
              <th>Participant</th>
              <th>Email</th>
              <th>Registered</th>
              <th>Status</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            @forelse($registrations as $reg)
            @php
              $rc = match($reg->registration_status){
                'confirmed' => 'badge-success',
                'attended'  => 'badge-info',
                'cancelled' => 'badge-danger',
                default     => 'badge-warning',
              };
            @endphp
            <tr>
              <td style="font-size:.82rem;font-weight:600;color:var(--dark)">{{ $reg->user?->first_name }} {{ $reg->user?->last_name }}</td>
              <td style="font-size:.78rem;color:var(--gray)">{{ $reg->user?->email }}</td>
              <td style="font-size:.75rem;color:var(--gray)">{{ $reg->registered_at?->format('M j, Y') }}</td>
              <td><span class="badge {{ $rc }}">{{ ucfirst($reg->registration_status) }}</span></td>
              <td>
                <form method="POST" action="{{ route('admin.registrations.status', $reg) }}" style="display:inline-flex;gap:4px">
                  @csrf @method('PATCH')
                  <select name="registration_status" class="form-control form-select" style="padding:4px 8px;font-size:.73rem;width:auto;height:auto" onchange="this.form.submit()">
                    @foreach(['pending','confirmed','cancelled','attended'] as $rs)
                    <option value="{{ $rs }}" {{ $reg->registration_status === $rs ? 'selected' : '' }}>{{ ucfirst($rs) }}</option>
                    @endforeach
                  </select>
                </form>
              </td>
            </tr>
            @empty
            <tr><td colspan="5" style="text-align:center;padding:24px;color:var(--gray)">No registrations yet.</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>
      @if($registrations->hasPages())
      <div style="padding:14px 20px;border-top:1px solid var(--border)">{{ $registrations->links() }}</div>
      @endif
    </div>
  </div>

  <!-- Stats sidebar -->
  <div>
    <div class="card" style="padding:18px">
      <div style="font-size:.82rem;font-weight:700;color:var(--dark);margin-bottom:14px;padding-bottom:12px;border-bottom:1px solid var(--border)">Enrollment Stats</div>
      @php
        $confirmed = $registrations->getCollection()->where('registration_status','confirmed')->count();
        $pending   = $registrations->getCollection()->where('registration_status','pending')->count();
        $attended  = $registrations->getCollection()->where('registration_status','attended')->count();
        $cancelled = $registrations->getCollection()->where('registration_status','cancelled')->count();
        $total     = $registrations->total();
        $pct       = $session->max_participants > 0 ? min(100, round($confirmed / $session->max_participants * 100)) : 0;
      @endphp
      @foreach([
        ['Confirmed', $confirmed, '#166534'],
        ['Pending',   $pending,   '#854d0e'],
        ['Attended',  $attended,  '#1e40af'],
        ['Cancelled', $cancelled, '#6b7280'],
      ] as [$lbl, $cnt, $clr])
      <div style="display:flex;justify-content:space-between;font-size:.8rem;margin-bottom:8px">
        <span style="color:var(--gray)">{{ $lbl }}</span>
        <strong style="color:{{ $clr }}">{{ $cnt }}</strong>
      </div>
      @endforeach
      <div style="margin-top:12px;padding-top:12px;border-top:1px solid var(--border)">
        <div style="font-size:.72rem;color:var(--gray);margin-bottom:6px">Capacity ({{ $confirmed }}/{{ $session->max_participants }})</div>
        @php $capColor = $pct >= 100 ? '#dc2626' : ($pct >= 75 ? '#f59e0b' : 'var(--forest)'); @endphp
        <div style="height:6px;border-radius:3px;background:var(--border);overflow:hidden">
          <div style="width:{{ $pct }}%;height:100%;background:{{ $capColor }};border-radius:3px"></div>
        </div>
        <div style="font-size:.68rem;color:var(--gray);margin-top:4px">{{ $pct }}% full</div>
      </div>
    </div>
  </div>
</div>
@endsection
