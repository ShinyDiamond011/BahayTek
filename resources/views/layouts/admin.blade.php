<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title>@yield('title','Dashboard') — BAHAYTEK Admin</title>
<meta name="csrf-token" content="{{ csrf_token() }}">
<link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&family=DM+Sans:wght@300;400;500;600;700&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet"/>
<style>
:root{
  --forest:#336a29;--green:#498428;--emerald:#80b155;--mint:#c1d95c;
  --cream:#f5f9f0;--sand:#edf5e3;--gold:#b68a3b;
  --dark:#1a2e12;--charcoal:#2e4a1e;--gray:#5a7248;--border:#c8ddb0;
  --red:#dc2626;--red-lt:#fee2e2;--blue:#1d4ed8;--white:#ffffff;
  --sh-sm:0 2px 8px rgba(51,106,41,.07);
  --sh-md:0 8px 24px rgba(51,106,41,.11);
  --sidebar-w:230px;
}
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
body{font-family:'DM Sans',sans-serif;background:#f0f4eb;color:var(--dark);min-height:100vh;display:flex}

/* ── SIDEBAR ── */
.sidebar{width:var(--sidebar-w);background:linear-gradient(180deg,#1a2e12 0%,#2a4a1e 100%);position:fixed;top:0;left:0;height:100vh;display:flex;flex-direction:column;z-index:100;overflow-y:auto}
.sidebar-logo{padding:24px 20px 20px;border-bottom:1px solid rgba(193,217,92,.1)}
.sidebar-wordmark{font-family:'DM Serif Display',serif;font-size:1.25rem;color:#fff;line-height:1}
.sidebar-wordmark .b{color:var(--mint)}
.sidebar-sub{font-size:.5rem;font-weight:700;letter-spacing:3px;text-transform:uppercase;color:rgba(193,217,92,.5);margin-top:3px;display:block}
.sidebar-badge{display:inline-block;margin-top:8px;padding:2px 10px;background:rgba(193,217,92,.12);border:1px solid rgba(193,217,92,.25);border-radius:20px;font-size:.6rem;font-weight:700;color:var(--mint);letter-spacing:1px;text-transform:uppercase}
.sidebar-nav{padding:16px 0;flex:1}
.nav-section{padding:12px 16px 4px;font-size:.6rem;font-weight:700;letter-spacing:2px;text-transform:uppercase;color:rgba(255,255,255,.25)}
.nav-item{display:flex;align-items:center;gap:10px;padding:9px 20px;font-size:.8rem;font-weight:500;color:rgba(255,255,255,.6);text-decoration:none;transition:all .2s;position:relative}
.nav-item svg{flex-shrink:0;opacity:.6;transition:opacity .2s}
.nav-item:hover{color:#fff;background:rgba(193,217,92,.07)}
.nav-item:hover svg{opacity:1}
.nav-item.active{color:#fff;background:rgba(193,217,92,.12);border-left:3px solid var(--mint)}
.nav-item.active svg{opacity:1}
.nav-item.active{padding-left:17px}
.sidebar-footer{padding:16px 20px;border-top:1px solid rgba(193,217,92,.1)}
.sidebar-user{font-size:.75rem;color:rgba(255,255,255,.5);margin-bottom:10px}
.sidebar-user strong{color:rgba(255,255,255,.85);display:block;font-size:.8rem}
.btn-logout{display:flex;align-items:center;gap:7px;padding:7px 14px;border-radius:8px;font-size:.75rem;font-weight:600;background:rgba(220,38,38,.15);color:#fca5a5;border:1px solid rgba(220,38,38,.2);cursor:pointer;font-family:inherit;transition:all .2s;text-decoration:none;width:100%;justify-content:center}
.btn-logout:hover{background:rgba(220,38,38,.25);color:#fff}

/* ── MAIN ── */
.main{margin-left:var(--sidebar-w);flex:1;display:flex;flex-direction:column;min-height:100vh}
.topbar{background:#fff;border-bottom:1px solid var(--border);padding:0 32px;height:60px;display:flex;align-items:center;justify-content:space-between;position:sticky;top:0;z-index:50}
.topbar-title{font-size:1rem;font-weight:700;color:var(--dark)}
.topbar-right{display:flex;align-items:center;gap:12px}
.topbar-staff{font-size:.78rem;color:var(--gray);font-weight:500}
.topbar-role{display:inline-block;padding:2px 10px;border-radius:20px;font-size:.62rem;font-weight:700;letter-spacing:.5px;text-transform:uppercase;background:var(--sand);color:var(--forest);border:1px solid var(--border)}
.content{padding:28px 32px;flex:1}

/* ── ALERTS ── */
.alert{padding:11px 16px;border-radius:10px;font-size:.82rem;font-weight:500;margin-bottom:20px;display:flex;align-items:center;gap:10px}
.alert-success{background:#dcfce7;color:#166534;border:1px solid #86efac}
.alert-error{background:var(--red-lt);color:var(--red);border:1px solid #fca5a5}

/* ── CARDS ── */
.card{background:#fff;border-radius:14px;border:1px solid var(--border);box-shadow:var(--sh-sm);overflow:hidden}
.card-header{padding:18px 22px;border-bottom:1px solid var(--border);display:flex;align-items:center;justify-content:space-between;gap:12px}
.card-title{font-size:.9rem;font-weight:700;color:var(--dark)}
.card-body{padding:22px}

/* ── BADGES ── */
.badge{display:inline-flex;align-items:center;padding:3px 10px;border-radius:20px;font-size:.68rem;font-weight:700;letter-spacing:.3px;text-transform:uppercase}
.badge-pending{background:#fef9c3;color:#854d0e;border:1px solid #fde68a}
.badge-confirmed{background:#dcfce7;color:#166534;border:1px solid #86efac}
.badge-declined{background:var(--red-lt);color:var(--red);border:1px solid #fca5a5}
.badge-cancelled{background:#f3f4f6;color:#6b7280;border:1px solid #d1d5db}
.badge-completed{background:var(--sand);color:var(--forest);border:1px solid var(--border)}
.badge-success{background:#dcfce7;color:#166534;border:1px solid #86efac}
.badge-warning{background:#fef9c3;color:#854d0e;border:1px solid #fde68a}
.badge-danger{background:#fee2e2;color:#991b1b;border:1px solid #fca5a5}
.badge-info{background:#dbeafe;color:#1e40af;border:1px solid #93c5fd}
.badge-secondary{background:#f3f4f6;color:#6b7280;border:1px solid #d1d5db}

/* ── TABLE ── */
.tbl{width:100%;border-collapse:collapse}
.tbl th{padding:9px 14px;font-size:.72rem;font-weight:700;color:var(--gray);text-transform:uppercase;letter-spacing:.5px;text-align:left;border-bottom:2px solid var(--border);white-space:nowrap}
.tbl td{padding:11px 14px;font-size:.82rem;color:var(--dark);border-bottom:1px solid #eef2ea;vertical-align:middle}
.tbl tr:last-child td{border-bottom:none}
.tbl tr:hover td{background:#fafcf8}
.tbl-link{color:var(--forest);font-weight:600;text-decoration:none}
.tbl-link:hover{text-decoration:underline}

/* ── FORM CONTROLS ── */
.form-select,.form-input,.form-control{padding:8px 12px;border-radius:8px;border:1.5px solid var(--border);font-size:.8rem;font-family:inherit;color:var(--dark);background:#fff;outline:none;transition:border-color .2s;width:100%;box-sizing:border-box}
.form-select:focus,.form-input:focus,.form-control:focus{border-color:var(--forest);box-shadow:0 0 0 3px rgba(51,106,41,.08)}
.form-control.error{border-color:#dc2626}
.btn-secondary{background:#f3f4f6;color:var(--dark);border:1px solid var(--border)}
.btn-secondary:hover{background:#e5e7eb}
.btn{display:inline-flex;align-items:center;gap:6px;padding:8px 16px;border-radius:9px;font-size:.8rem;font-weight:600;font-family:inherit;cursor:pointer;border:none;transition:all .2s;text-decoration:none}
.btn-primary{background:var(--forest);color:#fff}
.btn-primary:hover{background:var(--green)}
.btn-outline{background:transparent;color:var(--forest);border:1.5px solid var(--border)}
.btn-outline:hover{border-color:var(--forest);background:var(--sand)}
.btn-danger{background:var(--red-lt);color:var(--red);border:1px solid #fca5a5}
.btn-danger:hover{background:#fecaca}
.btn-sm{padding:5px 12px;font-size:.75rem}

/* ── PAGINATION ── */
.pagination-wrap{display:flex;justify-content:center;padding:16px 0 0;gap:4px}
.pagination-wrap a,.pagination-wrap span{padding:6px 12px;border-radius:8px;font-size:.78rem;font-weight:500;border:1px solid var(--border);color:var(--gray);text-decoration:none;transition:all .2s}
.pagination-wrap a:hover{border-color:var(--forest);color:var(--forest)}
.pagination-wrap span.active{background:var(--forest);color:#fff;border-color:var(--forest)}
.pagination-wrap span.disabled{opacity:.4;cursor:default}
</style>
@stack('styles')
</head>
<body>

<!-- SIDEBAR -->
<aside class="sidebar">
  <div class="sidebar-logo">
    <div class="sidebar-wordmark"><span class="b">BAHAY</span>TEK</div>
    <span class="sidebar-sub">Bahay Teknik</span>
    <span class="sidebar-badge">Admin</span>
  </div>

  <nav class="sidebar-nav">
    <div class="nav-section">Main</div>
    <a href="{{ route('admin.dashboard') }}" class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
      <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
      Dashboard
    </a>

    <div class="nav-section">Operations</div>
    <a href="{{ route('admin.bookings.index') }}" class="nav-item {{ request()->routeIs('admin.bookings*') ? 'active' : '' }}">
      <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
      Consultation Bookings
    </a>
    <a href="{{ route('admin.schedules.index') }}" class="nav-item {{ request()->routeIs('admin.schedules*') ? 'active' : '' }}" style="padding-left:36px;font-size:.76rem">
      <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
      Manage Slots
    </a>
    <a href="{{ route('admin.orders.index') }}" class="nav-item {{ request()->routeIs('admin.orders*') ? 'active' : '' }}">
      <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 01-8 0"/></svg>
      Orders & Payments
    </a>
    <a href="{{ route('admin.workshops.index') }}" class="nav-item {{ request()->routeIs('admin.workshops*') ? 'active' : '' }}">
      <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87"/><path d="M16 3.13a4 4 0 010 7.75"/></svg>
      Workshops
    </a>
    <a href="{{ route('admin.products.index') }}" class="nav-item {{ request()->routeIs('admin.products*') ? 'active' : '' }}">
      <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 16V8a2 2 0 00-1-1.73l-7-4a2 2 0 00-2 0l-7 4A2 2 0 003 8v8a2 2 0 001 1.73l7 4a2 2 0 002 0l7-4A2 2 0 0021 16z"/></svg>
      Products & Inventory
    </a>

    <div class="nav-section">People</div>
    <a href="{{ route('admin.users.index') }}" class="nav-item {{ request()->routeIs('admin.users*') ? 'active' : '' }}">
      <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
      Users
    </a>

    <div class="nav-section">Reports</div>
    <a href="{{ route('admin.reports.index') }}" class="nav-item {{ request()->routeIs('admin.reports*') ? 'active' : '' }}">
      <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg>
      Reports
    </a>
  </nav>

  <div class="sidebar-footer">
    <div class="sidebar-user">
      <strong>{{ Auth::guard('staff')->user()->full_name }}</strong>
      {{ ucfirst(Auth::guard('staff')->user()->role) }}
    </div>
    <form method="POST" action="{{ route('admin.logout') }}">
      @csrf
      <button type="submit" class="btn-logout">
        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
        Log Out
      </button>
    </form>
  </div>
</aside>

<!-- MAIN -->
<div class="main">
  <div class="topbar">
    <div class="topbar-title">@yield('page-title','Dashboard')</div>
    <div class="topbar-right">
      <span class="topbar-staff">{{ Auth::guard('staff')->user()->email }}</span>
      <span class="topbar-role">{{ Auth::guard('staff')->user()->role }}</span>
    </div>
  </div>

  <div class="content">
    @if(session('success'))
      <div class="alert alert-success">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
        {{ session('success') }}
      </div>
    @endif
    @if(session('error'))
      <div class="alert alert-error">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
        {{ session('error') }}
      </div>
    @endif

    @yield('content')
  </div>
</div>

@stack('scripts')
</body>
</html>
