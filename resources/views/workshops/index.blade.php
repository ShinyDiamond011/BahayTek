@extends('layouts.app')
@section('title','Workshops & Training')

@push('styles')
<style>
.page-hero{background:linear-gradient(135deg,#1a2e12 0%,#2a4a1e 50%,#336a29 100%);padding:72px 0 40px;position:relative;overflow:hidden}
.page-hero::before{content:'';position:absolute;inset:0;background:radial-gradient(ellipse 60% 80% at 80% 40%,rgba(193,217,92,.06) 0%,transparent 60%)}
.page-hero-inner{max-width:1120px;margin:0 auto;padding:0 40px;position:relative;z-index:1}
.page-hero h1{font-family:'DM Serif Display',serif;font-size:2.2rem;color:#fff;line-height:1.2}
.page-hero h1 em{font-style:italic;color:var(--mint)}
.page-hero p{font-size:.9rem;color:rgba(255,255,255,.65);margin-top:8px;max-width:520px}
.hero-actions{display:flex;gap:10px;margin-top:20px;flex-wrap:wrap}
.btn-hero-primary{display:inline-flex;align-items:center;gap:6px;padding:11px 22px;border-radius:10px;background:var(--mint);color:var(--dark);font-size:.84rem;font-weight:700;text-decoration:none;transition:all .2s}
.btn-hero-primary:hover{background:#eaf59d}
.btn-hero-secondary{display:inline-flex;align-items:center;gap:6px;padding:11px 22px;border-radius:10px;background:rgba(255,255,255,.1);color:#fff;font-size:.84rem;font-weight:600;text-decoration:none;border:1.5px solid rgba(255,255,255,.2);transition:all .2s}
.btn-hero-secondary:hover{background:rgba(255,255,255,.18)}

.workshops-wrap{max-width:1120px;margin:0 auto;padding:40px 40px}
.section-toolbar{display:flex;align-items:center;justify-content:space-between;gap:12px;margin-bottom:24px;flex-wrap:wrap}
.section-toolbar h2{font-family:'DM Serif Display',serif;font-size:1.4rem;color:var(--dark)}
.search-wrap{position:relative;min-width:200px}
.search-wrap svg{position:absolute;left:11px;top:50%;transform:translateY(-50%);color:var(--gray);pointer-events:none}
.search-input{padding:8px 12px 8px 34px;border-radius:9px;border:1.5px solid var(--border);font-size:.8rem;font-family:inherit;outline:none;color:var(--dark);background:#fff;transition:border-color .2s;width:100%}
.search-input:focus{border-color:var(--forest)}

.session-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:20px}
.session-card{background:#fff;border:1px solid var(--border);border-radius:16px;box-shadow:var(--sh-sm);overflow:hidden;display:flex;flex-direction:column;transition:all .2s}
.session-card:hover{box-shadow:var(--sh-md);transform:translateY(-2px)}
.session-card-top{padding:20px 20px 0;flex:1}
.session-badge-row{display:flex;gap:6px;margin-bottom:10px;flex-wrap:wrap}
.sess-badge{font-size:.62rem;font-weight:700;letter-spacing:.5px;text-transform:uppercase;padding:3px 9px;border-radius:20px}
.sess-upcoming{background:#dcfce7;color:#166534;border:1px solid #86efac}
.sess-ongoing{background:#dbeafe;color:#1e40af;border:1px solid #93c5fd}
.sess-completed{background:var(--sand);color:var(--forest);border:1px solid var(--border)}
.sess-cancelled{background:#f3f4f6;color:#6b7280;border:1px solid #d1d5db}
.sess-free{background:#fef9c3;color:#854d0e;border:1px solid #fde68a}

.session-title{font-size:1rem;font-weight:700;color:var(--dark);margin-bottom:8px;line-height:1.3}
.session-desc{font-size:.78rem;color:var(--gray);line-height:1.55;margin-bottom:14px;display:-webkit-box;-webkit-line-clamp:3;-webkit-box-orient:vertical;overflow:hidden}
.session-meta{display:flex;flex-direction:column;gap:6px;margin-bottom:16px}
.session-meta-row{display:flex;align-items:center;gap:7px;font-size:.78rem;color:var(--gray)}
.session-meta-row svg{color:var(--emerald);flex-shrink:0}
.session-card-foot{padding:0 20px 18px;border-top:1px solid var(--border);padding-top:14px;margin-top:auto}
.sess-price{font-size:1.1rem;font-weight:700;color:var(--dark);margin-bottom:10px}
.sess-price small{font-size:.72rem;font-weight:400;color:var(--gray)}
.slots-bar{height:4px;border-radius:2px;background:var(--border);margin-bottom:6px;overflow:hidden}
.slots-fill{height:100%;border-radius:2px;background:var(--forest);transition:width .3s}
.slots-fill.warn{background:#f59e0b}
.slots-fill.full{background:#dc2626}
.slots-text{font-size:.7rem;color:var(--gray);margin-bottom:10px}
.btn-enroll{display:block;width:100%;padding:10px;border-radius:10px;background:var(--forest);color:#fff;border:none;font-size:.82rem;font-weight:700;font-family:inherit;cursor:pointer;transition:all .2s;text-align:center;text-decoration:none}
.btn-enroll:hover{background:var(--green)}
.btn-enroll.enrolled{background:var(--sand);color:var(--forest);border:1.5px solid var(--border);cursor:default}
.btn-enroll.full{background:#f3f4f6;color:var(--gray);border:1.5px solid var(--border);cursor:not-allowed}

.empty-state{text-align:center;padding:64px 24px}
.empty-icon{width:72px;height:72px;border-radius:50%;background:var(--sand);display:flex;align-items:center;justify-content:center;margin:0 auto 16px}

@media(max-width:900px){.session-grid{grid-template-columns:repeat(2,1fr)}.workshops-wrap{padding:28px 18px}.page-hero-inner{padding:0 18px}}
@media(max-width:560px){.session-grid{grid-template-columns:1fr}}
</style>
@endpush

@section('content')
<div class="page-hero">
  <div class="page-hero-inner">
    <h1>Workshops &amp; <em>Training</em></h1>
    <p>Hands-on sustainable technology training — solar, biogas, aquaponics, organic farming, and more. Open to communities across Bicol.</p>
    <div class="hero-actions">
      @auth
        <a href="{{ route('workshops.my-enrollments') }}" class="btn-hero-secondary">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
          My Enrollments
        </a>
      @else
        <a href="{{ route('login') }}" class="btn-hero-primary">Sign In to Enroll</a>
      @endauth
    </div>
  </div>
</div>

<div class="workshops-wrap">
  @if(session('success'))
  <div style="background:#dcfce7;border:1px solid #86efac;color:#166534;padding:12px 18px;border-radius:10px;font-size:.84rem;font-weight:600;margin-bottom:20px;display:flex;align-items:center;gap:8px">
    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
    {{ session('success') }}
  </div>
  @endif
  @if(session('error'))
  <div style="background:#fee2e2;border:1px solid #fca5a5;color:#991b1b;padding:12px 18px;border-radius:10px;font-size:.84rem;font-weight:600;margin-bottom:20px">
    {{ session('error') }}
  </div>
  @endif

  {{-- Payment notice --}}
  <div style="background:#fef9c3;border:2px solid #f59e0b;border-radius:12px;padding:14px 20px;margin-bottom:24px;display:flex;align-items:flex-start;gap:12px">
    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#b45309" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0;margin-top:1px"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
    <div>
      <div style="font-size:.88rem;font-weight:700;color:#92400e;margin-bottom:3px">Payment Information</div>
      <div style="font-size:.82rem;color:#92400e;line-height:1.5">
        The registration fee for all workshops is <strong>paid on-site at the venue</strong> on the day of the session. No online payment is required to register.
      </div>
    </div>
  </div>

  {{-- Tab nav --}}
  <div style="display:flex;align-items:center;gap:8px;margin-bottom:20px;border-bottom:2px solid var(--border);padding-bottom:0">
    <a href="{{ route('workshops.index') }}" style="display:inline-block;padding:8px 18px;font-size:.84rem;font-weight:700;border-radius:8px 8px 0 0;text-decoration:none;border-bottom:2px solid transparent;margin-bottom:-2px;{{ !$showHistory ? 'color:var(--forest);border-bottom-color:var(--forest);background:var(--sand)' : 'color:var(--gray)' }}">
      Active Workshops
    </a>
    <a href="{{ route('workshops.index') }}?history=1" style="display:inline-block;padding:8px 18px;font-size:.84rem;font-weight:700;border-radius:8px 8px 0 0;text-decoration:none;border-bottom:2px solid transparent;margin-bottom:-2px;{{ $showHistory ? 'color:var(--forest);border-bottom-color:var(--forest);background:var(--sand)' : 'color:var(--gray)' }}">
      Workshop History
    </a>
  </div>

  <div class="section-toolbar">
    <h2>{{ $showHistory ? 'Past Workshops' : 'Upcoming Sessions' }}</h2>
    <form method="GET" action="{{ route('workshops.index') }}" style="display:flex;gap:8px;align-items:center">
      @if($showHistory)<input type="hidden" name="history" value="1">@endif
      <div class="search-wrap">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
        <input class="search-input" type="text" name="search" placeholder="Search workshops..." value="{{ request('search') }}">
      </div>
      @if(!$showHistory)
      <select style="padding:8px 12px;border-radius:9px;border:1.5px solid var(--border);font-size:.8rem;font-family:inherit;outline:none;color:var(--dark);background:#fff" name="status" onchange="this.form.submit()">
        <option value="">Open &amp; Ongoing</option>
        <option value="open" {{ request('status')==='open'?'selected':'' }}>Open</option>
        <option value="coming_soon" {{ request('status')==='coming_soon'?'selected':'' }}>Coming Soon</option>
        <option value="ongoing" {{ request('status')==='ongoing'?'selected':'' }}>Ongoing</option>
      </select>
      @endif
    </form>
  </div>

  @if($sessions->isEmpty())
  <div class="empty-state">
    <div class="empty-icon">
      <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="var(--emerald)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
    </div>
    <div style="font-size:1.1rem;font-weight:700;color:var(--dark);margin-bottom:8px">No sessions available</div>
    <div style="font-size:.85rem;color:var(--gray)">Check back soon for upcoming workshops and training programs.</div>
  </div>
  @else
  <div class="session-grid">
    @foreach($sessions as $session)
    @php
      $confirmedCount    = $session->confirmed_registrations_count ?? 0;
      $maxSlots          = $session->max_participants;
      $slotsLeft         = max(0, $maxSlots - $confirmedCount);
      $fillPct           = $maxSlots > 0 ? min(100, round($confirmedCount / $maxSlots * 100)) : 0;
      $fillClass         = $fillPct >= 100 ? 'full' : ($fillPct >= 75 ? 'warn' : '');
      $isFull            = $slotsLeft <= 0;
      $userStatus        = $userEnrollments[$session->id] ?? null;
      $regClosed         = $session->registration_deadline && now()->gt($session->registration_deadline);
      $badgeClass        = match($session->status) {
        'ongoing'     => 'sess-ongoing',
        'completed'   => 'sess-completed',
        'cancelled'   => 'sess-cancelled',
        'coming_soon' => 'sess-upcoming',
        default       => 'sess-upcoming',
      };
    @endphp
    <div class="session-card">
      <div class="session-card-top">
        <div class="session-badge-row">
          <span class="sess-badge {{ $badgeClass }}">{{ ucfirst($session->status) }}</span>
          @if((float)$session->fee === 0.0)
            <span class="sess-badge sess-free">Free</span>
          @endif
        </div>
        <div class="session-title">{{ $session->title }}</div>
        @if($session->description)
          <div class="session-desc">{{ $session->description }}</div>
        @endif
        <div class="session-meta">
          <div class="session-meta-row">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
            {{ $session->session_datetime->format('F j, Y · g:i A') }}
          </div>
          <div class="session-meta-row">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg>
            {{ $session->venue }}
          </div>
          <div class="session-meta-row">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87"/><path d="M16 3.13a4 4 0 010 7.75"/></svg>
            {{ $maxSlots }} max participants
          </div>
          @if($session->registration_deadline)
          <div class="session-meta-row" style="{{ $regClosed ? 'color:#dc2626;font-weight:600' : '' }}">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
            @if($regClosed)
              Registration closed
            @else
              Register by {{ $session->registration_deadline->format('M j, Y g:i A') }}
            @endif
          </div>
          @endif
        </div>
      </div>
      <div class="session-card-foot">
        <div class="sess-price">
          @if((float)$session->fee === 0.0)
            Free <small>of charge</small>
          @else
            ₱{{ number_format($session->fee, 2) }} <small>registration fee</small>
          @endif
        </div>
        @if($maxSlots > 0)
        <div class="slots-bar"><div class="slots-fill {{ $fillClass }}" style="width:{{ $fillPct }}%"></div></div>
        <div class="slots-text">
          @if($isFull) Session full
          @elseif($slotsLeft <= 5) Only {{ $slotsLeft }} slot{{ $slotsLeft !== 1 ? 's' : '' }} left
          @else {{ $slotsLeft }} slots available
          @endif
        </div>
        @endif

        @if($userStatus)
          <div class="btn-enroll enrolled">
            @if($userStatus === 'confirmed') ✓ Enrolled
            @elseif($userStatus === 'attended') ✓ Attended
            @elseif($userStatus === 'cancelled') Enrollment cancelled
            @else ⏳ Pending confirmation
            @endif
          </div>
        @elseif(in_array($session->status, ['cancelled', 'completed']))
          <div class="btn-enroll full">{{ ucfirst($session->status) }}</div>
        @elseif($regClosed)
          <div class="btn-enroll full">Registration Closed</div>
        @elseif($isFull)
          <div class="btn-enroll full">Session Full</div>
        @elseif(Auth::check())
          <form method="POST" action="{{ route('workshops.enroll', $session) }}">
            @csrf
            <button type="submit" class="btn-enroll">Enroll Now</button>
          </form>
        @else
          <a href="{{ route('login') }}" class="btn-enroll">Login to Enroll</a>
        @endif
      </div>
    </div>
    @endforeach
  </div>

  @if($sessions->hasPages())
  <div style="margin-top:32px">{{ $sessions->withQueryString()->links() }}</div>
  @endif
  @endif
</div>
@endsection
