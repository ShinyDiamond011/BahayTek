@extends('layouts.admin')
@section('title','Attendance')
@section('page-title','Workshop Attendance')

@section('content')
<div class="page-header">
  <div>
    <h1 class="page-title">Workshop Attendance</h1>
    <p class="page-sub">Mark participants as present or absent for ongoing/completed workshops.</p>
  </div>
</div>

@if(session('success'))
<div class="alert alert-success">{{ session('success') }}</div>
@endif

<div style="display:grid;grid-template-columns:260px 1fr;gap:20px;align-items:start">

  {{-- Session selector --}}
  <div class="card" style="padding:18px">
    <div style="font-size:.82rem;font-weight:700;color:var(--dark);margin-bottom:14px;padding-bottom:10px;border-bottom:1px solid var(--border)">Select Workshop</div>
    @forelse($sessions as $s)
    <a href="{{ route('admin.attendance.index') }}?session_id={{ $s->id }}"
       style="display:block;padding:10px 12px;border-radius:10px;text-decoration:none;margin-bottom:6px;border:1px solid {{ ($selectedSession && $selectedSession->id === $s->id) ? 'var(--forest)' : 'var(--border)' }};background:{{ ($selectedSession && $selectedSession->id === $s->id) ? 'var(--sand)' : '#fff' }}">
      <div style="font-size:.8rem;font-weight:700;color:var(--dark)">{{ Str::limit($s->title, 30) }}</div>
      <div style="font-size:.7rem;color:var(--gray);margin-top:2px">{{ $s->session_datetime->format('M j, Y · g:i A') }}</div>
      <span class="badge {{ $s->status === 'ongoing' ? 'badge-info' : 'badge-completed' }}" style="margin-top:5px;font-size:.6rem">{{ ucfirst($s->status) }}</span>
    </a>
    @empty
    <div style="font-size:.8rem;color:var(--gray);text-align:center;padding:20px 0">No ongoing or completed workshops yet.</div>
    @endforelse
  </div>

  {{-- Attendance list --}}
  <div>
    @if($selectedSession)
    <div class="card">
      <div style="padding:15px 20px;border-bottom:1px solid var(--border);display:flex;align-items:center;justify-content:space-between">
        <div>
          <div style="font-size:.88rem;font-weight:700;color:var(--dark)">{{ $selectedSession->title }}</div>
          <div style="font-size:.75rem;color:var(--gray);margin-top:2px">{{ $selectedSession->session_datetime->format('F j, Y · g:i A') }} · {{ $selectedSession->venue }}</div>
        </div>
        @php
          $presentCount = $registrations->where('registration_status','attended')->count();
          $totalCount   = $registrations->whereIn('registration_status',['confirmed','attended'])->count();
        @endphp
        <div style="text-align:right">
          <div style="font-size:1.4rem;font-weight:700;color:var(--forest)">{{ $presentCount }}/{{ $totalCount }}</div>
          <div style="font-size:.7rem;color:var(--gray)">present</div>
        </div>
      </div>

      <div class="tbl-wrap">
        <table class="tbl">
          <thead>
            <tr>
              <th>#</th>
              <th>Participant</th>
              <th>Email</th>
              <th>Phone</th>
              <th>Current Status</th>
              <th>Mark Attendance</th>
            </tr>
          </thead>
          <tbody>
            @forelse($registrations as $i => $reg)
            @php
              $rc = match($reg->registration_status){
                'attended'  => 'badge-info',
                'cancelled' => 'badge-danger',
                default     => 'badge-warning',
              };
            @endphp
            <tr>
              <td style="font-size:.78rem;color:var(--gray)">{{ $i + 1 }}</td>
              <td>
                <div style="font-size:.83rem;font-weight:700;color:var(--dark)">{{ $reg->user?->first_name }} {{ $reg->user?->last_name }}</div>
              </td>
              <td style="font-size:.78rem;color:var(--gray)">{{ $reg->user?->email }}</td>
              <td style="font-size:.78rem;color:var(--gray)">{{ $reg->user?->phone ?? '—' }}</td>
              <td><span class="badge {{ $rc }}">{{ ucfirst($reg->registration_status) }}</span></td>
              <td>
                @if($reg->registration_status !== 'cancelled')
                <div style="display:flex;gap:6px">
                  <form method="POST" action="{{ route('admin.attendance.mark', $reg) }}">
                    @csrf @method('PATCH')
                    <input type="hidden" name="present" value="1">
                    <button type="submit" class="btn btn-sm {{ $reg->registration_status === 'attended' ? 'btn-primary' : 'btn-secondary' }}" style="font-size:.72rem">
                      ✓ Present
                    </button>
                  </form>
                  <form method="POST" action="{{ route('admin.attendance.mark', $reg) }}">
                    @csrf @method('PATCH')
                    <input type="hidden" name="present" value="0">
                    <button type="submit" class="btn btn-sm btn-danger" style="font-size:.72rem">
                      ✗ Absent
                    </button>
                  </form>
                </div>
                @else
                <span style="font-size:.75rem;color:var(--gray)">Cancelled</span>
                @endif
              </td>
            </tr>
            @empty
            <tr><td colspan="6" style="text-align:center;padding:32px;color:var(--gray);font-size:.84rem">
              No confirmed registrations found for this workshop.
            </td></tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
    @else
    <div class="card" style="padding:48px 24px;text-align:center">
      <div style="font-size:2.5rem;margin-bottom:12px">📋</div>
      <div style="font-size:.9rem;font-weight:700;color:var(--dark);margin-bottom:6px">Select a workshop</div>
      <div style="font-size:.82rem;color:var(--gray)">Choose a workshop from the left panel to view and manage attendance.</div>
    </div>
    @endif
  </div>

</div>
@endsection
