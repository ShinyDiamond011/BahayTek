@extends('layouts.app')
@section('title','My Bookings')

@push('styles')
<style>
.page-header{padding:80px 48px 32px;max-width:900px;margin:0 auto}
.page-header h1{font-family:'DM Serif Display',serif;font-size:2rem;color:var(--dark);margin-bottom:6px}
.page-header p{color:var(--gray);font-size:.87rem}
.bookings-wrap{max-width:900px;margin:0 auto;padding:0 48px 56px}
.booking-card{background:#fff;border:1px solid var(--border);border-radius:14px;box-shadow:var(--sh-sm);margin-bottom:14px;overflow:hidden;transition:box-shadow .2s}
.booking-card:hover{box-shadow:var(--sh-md)}
.bc-header{padding:14px 20px;display:flex;align-items:center;justify-content:space-between;gap:12px;border-bottom:1px solid var(--border)}
.bc-id{font-size:.7rem;color:var(--gray);font-family:'DM Mono',monospace}
.badge{display:inline-flex;align-items:center;padding:3px 10px;border-radius:20px;font-size:.67rem;font-weight:700;letter-spacing:.3px;text-transform:uppercase}
.badge-pending{background:#fef9c3;color:#854d0e;border:1px solid #fde68a}
.badge-confirmed{background:#dcfce7;color:#166534;border:1px solid #86efac}
.badge-declined{background:var(--red-lt);color:var(--red);border:1px solid #fca5a5}
.badge-cancelled{background:#f3f4f6;color:#6b7280;border:1px solid #d1d5db}
.badge-completed{background:var(--sand);color:var(--forest);border:1px solid var(--border)}
.bc-body{padding:14px 20px;display:flex;align-items:flex-start;gap:28px;flex-wrap:wrap}
.bc-detail-label{font-size:.63rem;font-weight:700;letter-spacing:1.5px;text-transform:uppercase;color:var(--gray);margin-bottom:3px}
.bc-detail-val{font-size:.83rem;font-weight:600;color:var(--dark)}
.bc-footer{padding:10px 20px;background:var(--cream);border-top:1px solid var(--border);display:flex;align-items:center;justify-content:space-between;gap:10px;flex-wrap:wrap}
.bc-topic{font-size:.79rem;color:var(--gray);flex:1;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
.btn-cancel{padding:5px 13px;border-radius:8px;font-size:.73rem;font-weight:600;background:var(--red-lt);color:var(--red);border:1px solid #fca5a5;cursor:pointer;font-family:inherit;transition:all .2s}
.btn-cancel:hover{background:#fecaca}
.bc-note{padding:9px 20px;background:#fffbeb;border-top:1px solid #fde68a;font-size:.77rem;color:#854d0e}
.empty-state{text-align:center;padding:56px 24px}
.empty-icon{width:60px;height:60px;border-radius:50%;background:var(--sand);display:flex;align-items:center;justify-content:center;margin:0 auto 14px}
.empty-title{font-size:1.05rem;font-weight:700;color:var(--dark);margin-bottom:7px}
.empty-desc{color:var(--gray);font-size:.84rem;margin-bottom:22px}
.btn-primary{display:inline-flex;align-items:center;gap:7px;padding:10px 20px;border-radius:10px;background:var(--forest);color:#fff;text-decoration:none;font-size:.84rem;font-weight:700;transition:all .2s}
.btn-primary:hover{background:var(--green)}
.flash-success{background:#dcfce7;color:#166534;border:1px solid #86efac;padding:12px 16px;border-radius:10px;font-size:.83rem;font-weight:500;margin-bottom:14px}
</style>
@endpush

@section('content')
<div style="padding-top:62px">
  <div class="page-header">
    <h1>My Consultations</h1>
    <p>Track and manage your consultation booking requests.</p>
  </div>

  <div class="bookings-wrap">
    @if(session('success'))
      <div class="flash-success">{{ session('success') }}</div>
    @endif

    @if($bookings->isEmpty())
      <div class="booking-card">
        <div class="empty-state">
          <div class="empty-icon">
            <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="var(--emerald)" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
          </div>
          <div class="empty-title">No bookings yet</div>
          <p class="empty-desc">You haven't booked any consultations. Browse available slots and schedule your first session.</p>
          <a href="{{ route('consultation.index') }}" class="btn-primary">Book a Consultation</a>
        </div>
      </div>
    @else
      @foreach($bookings as $booking)
      <div class="booking-card">
        <div class="bc-header">
          <div>
            <div style="font-size:.82rem;font-weight:700;color:var(--dark)">
              {{ $booking->schedule?->type_label ?? 'Consultation' }}
            </div>
            <div class="bc-id">#{{ str_pad($booking->id, 5, '0', STR_PAD_LEFT) }}</div>
          </div>
          <span class="badge badge-{{ $booking->status }}">{{ ucfirst($booking->status) }}</span>
        </div>
        <div class="bc-body">
          <div>
            <div class="bc-detail-label">Date</div>
            <div class="bc-detail-val">{{ $booking->schedule?->date->format('M d, Y') ?? '—' }}</div>
          </div>
          <div>
            <div class="bc-detail-label">Time</div>
            <div class="bc-detail-val">{{ $booking->schedule?->time_range ?? '—' }}</div>
          </div>
          <div>
            <div class="bc-detail-label">Attendees</div>
            <div class="bc-detail-val">{{ $booking->no_attendees }}</div>
          </div>
          <div>
            <div class="bc-detail-label">Booked On</div>
            <div class="bc-detail-val">{{ $booking->booked_at->format('M d, Y') }}</div>
          </div>
        </div>
        <div class="bc-footer">
          <div class="bc-topic">
            <strong style="color:var(--charcoal)">Topic:</strong> {{ $booking->topic }}
          </div>
          @if(in_array($booking->status, ['pending','confirmed']))
          <form method="POST" action="{{ route('consultation.cancel', $booking) }}" onsubmit="return confirm('Cancel this booking?')">
            @csrf @method('DELETE')
            <button type="submit" class="btn-cancel">Cancel</button>
          </form>
          @endif
        </div>
        @if($booking->admin_notes)
        <div class="bc-note">
          <strong>Note from BAHAYTEK:</strong> {{ $booking->admin_notes }}
        </div>
        @endif
      </div>
      @endforeach

      <div style="margin-top:6px">{{ $bookings->links() }}</div>
    @endif

    <div style="margin-top:18px">
      <a href="{{ route('consultation.index') }}" class="btn-primary">
        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
        Book Another Consultation
      </a>
    </div>
  </div>
</div>
@endsection
