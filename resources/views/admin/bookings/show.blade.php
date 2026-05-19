@extends('layouts.admin')
@section('title','Booking #'.str_pad($booking->id,5,'0',STR_PAD_LEFT))
@section('page-title','Booking Detail')

@section('content')
<div style="margin-bottom:16px">
  <a href="{{ route('admin.bookings.index') }}" style="display:inline-flex;align-items:center;gap:6px;color:var(--gray);font-size:.8rem;text-decoration:none;font-weight:500">
    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg>
    Back to Bookings
  </a>
</div>

<div style="display:grid;grid-template-columns:1fr 310px;gap:18px;align-items:start">

  <!-- LEFT -->
  <div>
    <!-- ID + Status -->
    <div class="card" style="margin-bottom:16px">
      <div class="card-body">
        <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:12px;flex-wrap:wrap">
          <div>
            <div style="font-size:.67rem;font-weight:700;letter-spacing:1.5px;text-transform:uppercase;color:var(--gray);margin-bottom:4px">Booking Reference</div>
            <div style="font-family:'DM Mono',monospace;font-size:1.1rem;font-weight:700;color:var(--dark)">#{{ str_pad($booking->id,5,'0',STR_PAD_LEFT) }}</div>
          </div>
          <div style="text-align:right">
            <span class="badge badge-{{ $booking->status }}" style="font-size:.74rem;padding:5px 14px">{{ ucfirst($booking->status) }}</span>
            <div style="font-size:.7rem;color:var(--gray);margin-top:5px">Submitted {{ $booking->booked_at->format('M d, Y \a\t g:i A') }}</div>
          </div>
        </div>
      </div>
    </div>

    <!-- Schedule & Consultation Details -->
    <div class="card" style="margin-bottom:16px">
      <div class="card-header"><div class="card-title">Consultation Details</div></div>
      <div class="card-body">
        <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:18px">
          <div>
            <div style="font-size:.65rem;font-weight:700;letter-spacing:1.5px;text-transform:uppercase;color:var(--gray);margin-bottom:5px">Service Type</div>
            <div style="font-size:.87rem;font-weight:600;color:var(--dark)">{{ $booking->schedule?->type_label ?? '—' }}</div>
          </div>
          <div>
            <div style="font-size:.65rem;font-weight:700;letter-spacing:1.5px;text-transform:uppercase;color:var(--gray);margin-bottom:5px">Date</div>
            <div style="font-size:.87rem;font-weight:600;color:var(--dark)">
              {{ $booking->schedule?->date->format('l, F d, Y') ?? '—' }}
            </div>
          </div>
          <div>
            <div style="font-size:.65rem;font-weight:700;letter-spacing:1.5px;text-transform:uppercase;color:var(--gray);margin-bottom:5px">Time</div>
            <div style="font-size:.87rem;font-weight:600;color:var(--dark)">{{ $booking->schedule?->time_range ?? '—' }}</div>
          </div>
          <div>
            <div style="font-size:.65rem;font-weight:700;letter-spacing:1.5px;text-transform:uppercase;color:var(--gray);margin-bottom:5px">Attendees</div>
            <div style="font-size:.87rem;font-weight:600;color:var(--dark)">{{ $booking->no_attendees }}</div>
          </div>
          @if($booking->organization)
          <div style="grid-column:span 2">
            <div style="font-size:.65rem;font-weight:700;letter-spacing:1.5px;text-transform:uppercase;color:var(--gray);margin-bottom:5px">Organization</div>
            <div style="font-size:.87rem;font-weight:600;color:var(--dark)">{{ $booking->organization }}</div>
          </div>
          @endif
        </div>

        <div style="margin-top:18px;padding-top:16px;border-top:1px solid var(--border)">
          <div style="font-size:.65rem;font-weight:700;letter-spacing:1.5px;text-transform:uppercase;color:var(--gray);margin-bottom:5px">Topic</div>
          <div style="font-size:.9rem;font-weight:600;color:var(--dark);margin-bottom:10px">{{ $booking->topic }}</div>
          @if($booking->description)
          <div style="font-size:.65rem;font-weight:700;letter-spacing:1.5px;text-transform:uppercase;color:var(--gray);margin-bottom:5px">Description</div>
          <div style="font-size:.82rem;color:var(--charcoal);line-height:1.7;background:var(--cream);padding:12px 14px;border-radius:9px;border:1px solid var(--border)">{{ $booking->description }}</div>
          @endif
        </div>

        @if($booking->is_research_collab)
        <div style="margin-top:16px;padding:12px 14px;background:#eff6ff;border-radius:10px;border:1px solid #bfdbfe">
          <div style="font-size:.74rem;font-weight:700;color:#1d4ed8;margin-bottom:7px;display:flex;align-items:center;gap:6px">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
            Research Collaboration Request
          </div>
          <div style="display:flex;gap:28px;font-size:.82rem;flex-wrap:wrap">
            @if($booking->collab_institution)
            <div>
              <div style="font-size:.64rem;font-weight:700;text-transform:uppercase;letter-spacing:1px;color:#1d4ed8;opacity:.7;margin-bottom:3px">Institution</div>
              <div style="font-weight:600;color:#1e3a8a">{{ $booking->collab_institution }}</div>
            </div>
            @endif
            @if($booking->collab_type)
            <div>
              <div style="font-size:.64rem;font-weight:700;text-transform:uppercase;letter-spacing:1px;color:#1d4ed8;opacity:.7;margin-bottom:3px">Type</div>
              <div style="font-weight:600;color:#1e3a8a">{{ ucwords(str_replace('_',' ',$booking->collab_type)) }}</div>
            </div>
            @endif
          </div>
        </div>
        @endif
      </div>
    </div>

    <!-- Client Info -->
    <div class="card">
      <div class="card-header"><div class="card-title">Client Information</div></div>
      <div class="card-body">
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px">
          <div>
            <div style="font-size:.65rem;font-weight:700;letter-spacing:1.5px;text-transform:uppercase;color:var(--gray);margin-bottom:5px">Full Name</div>
            <div style="font-size:.87rem;font-weight:600;color:var(--dark)">{{ $booking->full_name }}</div>
          </div>
          <div>
            <div style="font-size:.65rem;font-weight:700;letter-spacing:1.5px;text-transform:uppercase;color:var(--gray);margin-bottom:5px">Email</div>
            <a href="mailto:{{ $booking->email }}" style="font-size:.84rem;font-weight:600;color:var(--forest)">{{ $booking->email }}</a>
          </div>
          @if($booking->phone)
          <div>
            <div style="font-size:.65rem;font-weight:700;letter-spacing:1.5px;text-transform:uppercase;color:var(--gray);margin-bottom:5px">Phone</div>
            <div style="font-size:.84rem;font-weight:600;color:var(--dark)">{{ $booking->phone }}</div>
          </div>
          @endif
          @if($booking->user)
          <div>
            <div style="font-size:.65rem;font-weight:700;letter-spacing:1.5px;text-transform:uppercase;color:var(--gray);margin-bottom:5px">Account</div>
            <a href="{{ route('admin.users.show', $booking->user) }}" style="font-size:.81rem;font-weight:600;color:var(--forest);text-decoration:none">View User Profile →</a>
          </div>
          @endif
        </div>
      </div>
    </div>
  </div>

  <!-- RIGHT: Actions -->
  <div>
    <div class="card">
      <div class="card-header"><div class="card-title">Update Status</div></div>
      <div class="card-body">
        <form method="POST" action="{{ route('admin.bookings.status', $booking) }}">
          @csrf @method('PATCH')
          <div style="margin-bottom:12px">
            <label style="font-size:.74rem;font-weight:600;color:var(--charcoal);display:block;margin-bottom:5px">Status</label>
            <select name="status" class="form-select" style="width:100%">
              @foreach(['pending','confirmed','declined','completed'] as $s)
              <option value="{{ $s }}" {{ $booking->status===$s?'selected':'' }}>{{ ucfirst($s) }}</option>
              @endforeach
            </select>
          </div>
          <div style="margin-bottom:14px">
            <label style="font-size:.74rem;font-weight:600;color:var(--charcoal);display:block;margin-bottom:5px">Note for Client <span style="font-weight:400;color:var(--gray)">(optional)</span></label>
            <textarea name="admin_notes" class="form-select" rows="4" style="width:100%;resize:vertical;font-family:inherit" placeholder="e.g. Confirmed. Please bring your proposal document.">{{ old('admin_notes', $booking->admin_notes) }}</textarea>
          </div>
          <button type="submit" class="btn btn-primary" style="width:100%">Save Changes</button>
        </form>
      </div>
    </div>

    <!-- Schedule Slot Info -->
    @if($booking->schedule)
    <div class="card" style="margin-top:14px">
      <div class="card-header"><div class="card-title">Schedule Slot</div></div>
      <div class="card-body" style="padding:14px 18px">
        <div style="font-size:.82rem;color:var(--dark)">
          <div style="display:flex;justify-content:space-between;margin-bottom:6px">
            <span style="color:var(--gray);font-size:.72rem;font-weight:600;text-transform:uppercase;letter-spacing:1px">Date</span>
            <span style="font-weight:600">{{ $booking->schedule->date->format('M d, Y') }}</span>
          </div>
          <div style="display:flex;justify-content:space-between;margin-bottom:6px">
            <span style="color:var(--gray);font-size:.72rem;font-weight:600;text-transform:uppercase;letter-spacing:1px">Time</span>
            <span style="font-weight:600">{{ $booking->schedule->time_range }}</span>
          </div>
          <div style="display:flex;justify-content:space-between">
            <span style="color:var(--gray);font-size:.72rem;font-weight:600;text-transform:uppercase;letter-spacing:1px">Slot Status</span>
            <span class="badge {{ $booking->schedule->status==='booked'?'badge-pending':'badge-confirmed' }}">{{ ucfirst($booking->schedule->status) }}</span>
          </div>
        </div>
      </div>
    </div>
    @endif

    <!-- Timeline -->
    <div class="card" style="margin-top:14px">
      <div class="card-header"><div class="card-title">Timeline</div></div>
      <div class="card-body" style="padding:14px 18px">
        <div style="display:flex;flex-direction:column;gap:10px">
          <div style="display:flex;gap:10px;align-items:flex-start">
            <div style="width:8px;height:8px;border-radius:50%;background:var(--emerald);flex-shrink:0;margin-top:4px"></div>
            <div>
              <div style="font-size:.77rem;font-weight:600;color:var(--dark)">Booking submitted</div>
              <div style="font-size:.7rem;color:var(--gray)">{{ $booking->booked_at->format('M d, Y · g:i A') }}</div>
            </div>
          </div>
          @if($booking->status !== 'pending')
          <div style="display:flex;gap:10px;align-items:flex-start">
            <div style="width:8px;height:8px;border-radius:50%;
              background:{{ match($booking->status) { 'confirmed','completed' => '#16a34a', 'declined' => '#dc2626', 'cancelled' => '#9ca3af', default => '#9ca3af' } }};
              flex-shrink:0;margin-top:4px"></div>
            <div>
              <div style="font-size:.77rem;font-weight:600;color:var(--dark)">{{ ucfirst($booking->status) }}</div>
              <div style="font-size:.7rem;color:var(--gray)">
                {{ ($booking->cancelled_at ?? $booking->updated_at)->format('M d, Y · g:i A') }}
              </div>
            </div>
          </div>
          @endif
        </div>
      </div>
    </div>
  </div>

</div>
@endsection
