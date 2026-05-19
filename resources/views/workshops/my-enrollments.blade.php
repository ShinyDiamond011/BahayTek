@extends('layouts.app')
@section('title','My Enrollments')

@push('styles')
<style>
.page-hero{background:linear-gradient(135deg,#1a2e12 0%,#2a4a1e 50%,#336a29 100%);padding:40px 0 28px;position:relative}
.page-hero::before{content:'';position:absolute;inset:0;background:radial-gradient(ellipse 60% 80% at 80% 40%,rgba(193,217,92,.06) 0%,transparent 60%)}
.page-hero-inner{max-width:1120px;margin:0 auto;padding:0 40px;position:relative;z-index:1}
.page-hero h1{font-family:'DM Serif Display',serif;font-size:1.8rem;color:#fff}
.page-hero p{font-size:.82rem;color:rgba(255,255,255,.55);margin-top:4px}
.enroll-wrap{max-width:900px;margin:0 auto;padding:32px 40px}
.enroll-card{background:#fff;border:1px solid var(--border);border-radius:14px;box-shadow:var(--sh-sm);padding:20px;margin-bottom:14px;display:grid;grid-template-columns:1fr auto;gap:16px;align-items:start;transition:box-shadow .2s}
.enroll-card:hover{box-shadow:var(--sh-md)}
.ec-title{font-size:.9rem;font-weight:700;color:var(--dark);margin-bottom:6px}
.ec-meta{display:flex;flex-direction:column;gap:4px}
.ec-meta-row{display:flex;align-items:center;gap:6px;font-size:.78rem;color:var(--gray)}
.badge{font-size:.62rem;font-weight:700;letter-spacing:.5px;text-transform:uppercase;padding:4px 12px;border-radius:20px}
.badge-pending{background:#fef9c3;color:#854d0e;border:1px solid #fde68a}
.badge-confirmed{background:#dcfce7;color:#166534;border:1px solid #86efac}
.badge-cancelled{background:#f3f4f6;color:#6b7280;border:1px solid #d1d5db}
.badge-attended{background:#dbeafe;color:#1e40af;border:1px solid #93c5fd}
.empty-state{text-align:center;padding:64px 24px}
.empty-icon{width:72px;height:72px;border-radius:50%;background:var(--sand);display:flex;align-items:center;justify-content:center;margin:0 auto 16px}
@media(max-width:640px){.enroll-wrap{padding:20px 18px}.page-hero-inner{padding:0 18px}.enroll-card{grid-template-columns:1fr}}
</style>
@endpush

@section('content')
<div class="page-hero">
  <div class="page-hero-inner">
    <h1>My Enrollments</h1>
    <p>Track your workshop registrations and training attendance.</p>
  </div>
</div>

<div class="enroll-wrap">
  <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px">
    <div style="font-size:.88rem;font-weight:700;color:var(--dark)">{{ $registrations->total() }} enrollment{{ $registrations->total() !== 1 ? 's' : '' }}</div>
    <a href="{{ route('workshops.index') }}" style="font-size:.8rem;color:var(--forest);font-weight:600;text-decoration:none">← Browse Workshops</a>
  </div>

  @if($registrations->isEmpty())
  <div class="empty-state">
    <div class="empty-icon">
      <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="var(--emerald)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
    </div>
    <div style="font-size:1.1rem;font-weight:700;color:var(--dark);margin-bottom:8px">No enrollments yet</div>
    <div style="font-size:.85rem;color:var(--gray);margin-bottom:22px">Browse our upcoming workshops and sign up for training sessions.</div>
    <a href="{{ route('workshops.index') }}" style="display:inline-flex;align-items:center;gap:6px;padding:11px 22px;border-radius:10px;background:var(--forest);color:#fff;text-decoration:none;font-size:.85rem;font-weight:700">
      Browse Workshops
    </a>
  </div>
  @else
  @foreach($registrations as $reg)
  @php
    $statusClass = match($reg->registration_status){
      'confirmed' => 'badge-confirmed',
      'attended'  => 'badge-attended',
      'cancelled' => 'badge-cancelled',
      default     => 'badge-pending',
    };
  @endphp
  <div class="enroll-card">
    <div>
      <div class="ec-title">{{ $reg->trainingSession?->title ?? 'Session removed' }}</div>
      <div class="ec-meta">
        @if($reg->trainingSession)
        <div class="ec-meta-row">
          <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
          {{ $reg->trainingSession->session_datetime->format('F j, Y · g:i A') }}
        </div>
        <div class="ec-meta-row">
          <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg>
          {{ $reg->trainingSession->venue }}
        </div>
        <div class="ec-meta-row">
          Fee: {{ (float)$reg->trainingSession->fee === 0.0 ? 'Free' : '₱'.number_format($reg->trainingSession->fee,2) }}
        </div>
        @endif
        <div class="ec-meta-row" style="color:var(--gray);font-size:.72rem">
          Enrolled {{ $reg->registered_at?->format('M j, Y') }}
        </div>
      </div>
    </div>
    <span class="badge {{ $statusClass }}">{{ ucfirst($reg->registration_status) }}</span>
  </div>
  @endforeach

  @if($registrations->hasPages())
  <div style="margin-top:16px">{{ $registrations->links() }}</div>
  @endif
  @endif
</div>
@endsection
