<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title>@yield('title', 'BAHAYTEK') — BAHAYTEK</title>
<meta name="csrf-token" content="{{ csrf_token() }}">
<link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&family=DM+Sans:wght@300;400;500;600;700&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet"/>
<style>
:root{
  --forest:#336a29;--green:#498428;--emerald:#80b155;--mint:#c1d95c;
  --cream:#f5f9f0;--sand:#edf5e3;--gold:#b68a3b;--gold-lt:#fef3c7;
  --dark:#1a2e12;--charcoal:#2e4a1e;--gray:#5a7248;--border:#c8ddb0;
  --red:#dc2626;--red-lt:#fee2e2;--blue:#1d4ed8;--purple:#6d28d9;
  --purple-lt:#ede9fe;--white:#ffffff;
  --sh-sm:0 2px 8px rgba(51,106,41,.07);
  --sh-md:0 8px 24px rgba(51,106,41,.11);
  --sh-lg:0 20px 48px rgba(51,106,41,.15);
}
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
html{scroll-behavior:smooth}
body{font-family:'DM Sans',sans-serif;background:var(--cream);color:var(--dark);overflow-x:hidden}

/* ══ NAV ══ */
.nav{position:fixed;top:0;left:0;right:0;z-index:200;background:rgba(51,106,41,.97);backdrop-filter:blur(16px);border-bottom:1px solid rgba(193,217,92,.15);height:62px;display:flex;align-items:center;padding:0 52px;justify-content:space-between;transition:background .3s;line-height:1}
.nav-logo{text-decoration:none;display:flex;flex-direction:column;line-height:1;flex-shrink:0;height:62px;align-items:center;justify-content:center}
.nav-logo .brand{font-family:'DM Serif Display',serif;font-size:1.15rem;color:#fff}
.nav-logo .brand .b{color:var(--mint)}
.nav-logo .sub{font-size:.5rem;font-weight:600;letter-spacing:2.5px;text-transform:uppercase;color:rgba(193,217,92,.7);margin-top:2px}
.nav-links{display:flex;gap:28px;flex-wrap:nowrap;flex-shrink:0}
.nav-links a{color:rgba(255,255,255,.7);text-decoration:none;font-size:.8rem;font-weight:500;transition:color .2s;position:relative;padding-bottom:2px;outline:none;white-space:nowrap;flex-shrink:0}
.nav-links a::after{content:'';position:absolute;bottom:-1px;left:0;right:0;height:1.5px;background:var(--mint);transform:scaleX(0);transition:transform .25s;transform-origin:left}
.nav-links a:hover,.nav-links a.active{color:#fff}
.nav-links a.active::after,.nav-links a:hover::after{transform:scaleX(1)}
.btn-nav-outline{padding:7px 18px;border-radius:8px;font-size:.78rem;font-weight:600;background:transparent;border:1.5px solid rgba(193,217,92,.4);color:#fff;cursor:pointer;font-family:inherit;transition:all .2s;text-decoration:none;display:flex;align-items:center;flex-shrink:0}
.btn-nav-outline:hover{border-color:var(--mint);background:rgba(193,217,92,.1)}
.btn-nav-solid{padding:7px 18px;border-radius:8px;font-size:.78rem;font-weight:700;background:var(--mint);color:var(--forest);border:none;cursor:pointer;font-family:inherit;transition:all .2s;text-decoration:none;display:flex;align-items:center;flex-shrink:0}
.btn-nav-solid:hover{background:#eaf59d}
.nav-actions{display:flex;gap:8px;align-items:center;flex-shrink:0;height:62px;justify-content:center}
.nav-user-name{color:#fff;font-size:.8rem;font-weight:600;opacity:.85}

/* ══ SECTION COMMONS ══ */
.section-eyebrow{font-size:.67rem;font-weight:700;letter-spacing:3px;text-transform:uppercase;color:var(--emerald);margin-bottom:8px;display:flex;align-items:center;gap:10px}
.section-eyebrow::before{content:'';width:20px;height:2px;background:var(--emerald);flex-shrink:0}
.section-heading{font-family:'DM Serif Display',serif;font-size:2.2rem;color:var(--dark);margin-bottom:12px;line-height:1.2}
.section-heading em{font-style:italic;color:var(--emerald)}
.section-sub{color:var(--gray);font-size:.88rem;line-height:1.75;max-width:500px;margin-bottom:48px}

/* ══ ALERTS ══ */
.flash-success{background:#dcfce7;color:#166534;border:1px solid #86efac;padding:12px 18px;border-radius:10px;font-size:.84rem;font-weight:500;margin-bottom:16px}
.flash-error{background:var(--red-lt);color:var(--red);border:1px solid #fca5a5;padding:12px 18px;border-radius:10px;font-size:.84rem;font-weight:500;margin-bottom:16px}

/* ══ FOOTER ══ */
footer{background:var(--dark);color:rgba(255,255,255,.55);text-align:center;padding:32px;font-size:.78rem}
footer a{color:rgba(255,255,255,.55);text-decoration:none}
footer a:hover{color:var(--mint)}

@media(max-width:768px){
  .nav{padding:0 18px}
  .nav-links{display:none}
}
</style>
@stack('styles')
</head>
<body>

<nav class="nav">
  <a href="{{ route('home') }}" class="nav-logo">
    <span class="brand"><span class="b">BAHAY</span>TEK</span>
    <span class="sub">Bahay Teknik</span>
  </a>
  <div class="nav-links">
    <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">Home</a>
    <a href="{{ route('shop.index') }}" class="{{ request()->routeIs('shop*') ? 'active' : '' }}">Shop</a>
    <a href="{{ route('workshops.index') }}" class="{{ request()->routeIs('workshops*') ? 'active' : '' }}">Workshops</a>
    <a href="{{ route('consultation.index') }}" class="{{ request()->routeIs('consultation*') ? 'active' : '' }}">Consultation</a>
  </div>
  <div class="nav-actions">
    @auth
      <span class="nav-user-name">{{ Auth::user()->first_name }}</span>
      <form method="POST" action="{{ route('logout') }}" style="display:inline">
        @csrf
        <button type="submit" class="btn-nav-outline">Log Out</button>
      </form>
    @else
      <a href="{{ route('login') }}" class="btn-nav-outline">Log In</a>
      <a href="{{ route('register') }}" class="btn-nav-solid">Sign Up</a>
    @endauth
  </div>
</nav>

@yield('content')

<footer>
  <p>© {{ date('Y') }} Bahay Teknik · Bicol, Philippines · <a href="#">Privacy Policy</a> · <a href="#">Terms</a></p>
</footer>

@stack('scripts')
</body>
</html>
