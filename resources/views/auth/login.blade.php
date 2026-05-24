<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title>{{ $activeTab === 'register' ? 'Sign Up' : 'Log In' }} — BAHAYTEK</title>
<meta name="csrf-token" content="{{ csrf_token() }}">
<link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&family=DM+Sans:wght@300;400;500;600;700&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet"/>
<style>
:root{
  --forest:#336a29;--green:#498428;--emerald:#80b155;--mint:#c1d95c;
  --cream:#f5f9f0;--sand:#edf5e3;--gold:#b68a3b;
  --dark:#1a2e12;--charcoal:#2e4a1e;--gray:#5a7248;--border:#c8ddb0;
  --red:#dc2626;--red-lt:#fee2e2;--white:#ffffff;
  --sh-lg:0 24px 64px rgba(51,106,41,.18);
}
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
html{scroll-behavior:smooth}
body{font-family:'DM Sans',sans-serif;background:var(--cream);color:var(--dark);min-height:100vh;display:flex;flex-direction:column}
.nav{position:fixed;top:0;left:0;right:0;z-index:200;background:rgba(51,106,41,.97);backdrop-filter:blur(16px);border-bottom:1px solid rgba(193,217,92,.15);height:62px;display:flex;align-items:center;padding:0 52px;justify-content:space-between}
.nav-logo{text-decoration:none;display:flex;flex-direction:column;line-height:1;height:62px;align-items:center;justify-content:center}
.nav-logo .brand{font-family:'DM Serif Display',serif;font-size:1.15rem;color:#fff}
.nav-logo .brand .b{color:var(--mint)}
.nav-logo .sub{font-size:.5rem;font-weight:600;letter-spacing:2.5px;text-transform:uppercase;color:rgba(193,217,92,.7);margin-top:2px}
.nav-links{display:flex;gap:28px}
.nav-links a{color:rgba(255,255,255,.7);text-decoration:none;font-size:.8rem;font-weight:500;transition:color .2s;position:relative;padding-bottom:2px;white-space:nowrap}
.nav-links a::after{content:'';position:absolute;bottom:-1px;left:0;right:0;height:1.5px;background:var(--mint);transform:scaleX(0);transition:transform .25s;transform-origin:left}
.nav-links a:hover{color:#fff}
.nav-links a:hover::after{transform:scaleX(1)}
.btn-nav-outline{padding:7px 18px;border-radius:8px;font-size:.78rem;font-weight:600;background:transparent;border:1.5px solid rgba(193,217,92,.4);color:#fff;cursor:pointer;font-family:inherit;transition:all .2s;text-decoration:none;display:flex;align-items:center}
.btn-nav-outline:hover{border-color:var(--mint);background:rgba(193,217,92,.1)}
.btn-nav-solid{padding:7px 18px;border-radius:8px;font-size:.78rem;font-weight:700;background:var(--mint);color:var(--forest);border:none;cursor:pointer;font-family:inherit;transition:all .2s;text-decoration:none;display:flex;align-items:center}
.btn-nav-solid:hover{background:#eaf59d}
.nav-actions{display:flex;gap:8px;align-items:center;height:62px}
.page-bg{flex:1;display:flex;align-items:center;justify-content:center;padding:82px 20px 48px;position:relative;overflow:hidden;min-height:100vh}
.page-bg::before{content:'';position:absolute;inset:0;background:
  radial-gradient(ellipse 70% 60% at 15% 50%,rgba(51,106,41,.07) 0%,transparent 60%),
  radial-gradient(ellipse 50% 50% at 85% 60%,rgba(128,177,85,.05) 0%,transparent 55%),
  linear-gradient(155deg,#edf5e3 0%,#f5f9f0 50%,#edf5e3 100%)}
.auth-card{display:flex;width:100%;max-width:780px;border-radius:24px;box-shadow:var(--sh-lg);overflow:hidden;border:1px solid var(--border);position:relative;z-index:1}
.auth-left{width:272px;flex-shrink:0;background:linear-gradient(160deg,#1e4d18 0%,#2a6622 40%,#336a29 70%,#3d7a31 100%);padding:40px 28px;display:flex;flex-direction:column;justify-content:space-between;position:relative;overflow:hidden}
.auth-left::before{content:'';position:absolute;inset:0;background:radial-gradient(ellipse 80% 50% at 80% 20%,rgba(193,217,92,.08) 0%,transparent 60%),radial-gradient(ellipse 60% 60% at 20% 80%,rgba(74,222,128,.06) 0%,transparent 55%)}
.auth-left-top{position:relative;z-index:1}
.al-wordmark{font-family:'DM Serif Display',serif;font-size:1.6rem;color:#fff;letter-spacing:.5px;line-height:1}
.al-wordmark .b{color:var(--mint)}
.al-sub{font-size:.55rem;font-weight:600;letter-spacing:3px;text-transform:uppercase;color:rgba(193,217,92,.6);margin-top:4px;display:block}
.al-tagline{margin-top:28px;font-size:1rem;font-weight:700;color:#fff;line-height:1.45;letter-spacing:-.01em}
.al-tagline em{color:var(--mint);font-style:normal}
.al-desc{margin-top:10px;font-size:.75rem;color:rgba(255,255,255,.55);line-height:1.6}
.al-features{margin-top:22px;display:flex;flex-direction:column;gap:9px}
.al-feat{display:flex;align-items:center;gap:9px;font-size:.74rem;color:rgba(255,255,255,.75);font-weight:500}
.al-feat-dot{width:18px;height:18px;border-radius:50%;background:rgba(193,217,92,.18);border:1.5px solid rgba(193,217,92,.35);display:flex;align-items:center;justify-content:center;flex-shrink:0}
.al-feat-dot svg{width:9px;height:9px}
.auth-left-bottom{position:relative;z-index:1}
.al-svg{width:100%;opacity:.18;margin-bottom:8px}
.al-copy{font-size:.64rem;color:rgba(255,255,255,.3);font-weight:500}
.auth-right{flex:1;background:var(--white);display:flex;flex-direction:column;min-width:0}
.auth-tabs{display:flex;padding:0 32px;border-bottom:1px solid var(--border);gap:4px;background:var(--white)}
.auth-tab{padding:18px 20px 16px;font-size:.82rem;font-weight:600;color:var(--gray);background:none;border:none;cursor:pointer;font-family:inherit;transition:all .2s;border-bottom:2.5px solid transparent;margin-bottom:-1px;white-space:nowrap}
.auth-tab.active{color:var(--forest);border-bottom-color:var(--forest)}
.auth-tab:hover:not(.active){color:var(--dark)}
.auth-body{padding:28px 32px 32px;flex:1}
.tab-panel{display:none}
.tab-panel.active{display:block}
.auth-welcome{font-size:1.05rem;font-weight:700;color:var(--dark);margin-bottom:4px}
.auth-welcome-sub{font-size:.78rem;color:var(--gray);margin-bottom:22px}
.divider{display:flex;align-items:center;gap:10px;margin-bottom:18px;color:var(--gray);font-size:.72rem;font-weight:500}
.divider::before,.divider::after{content:'';flex:1;height:1px;background:var(--border)}
.alert{padding:10px 14px;border-radius:9px;font-size:.78rem;font-weight:500;margin-bottom:14px;line-height:1.4}
.alert.error{background:var(--red-lt);color:var(--red);border:1px solid #fca5a5}
.alert.success{background:#dcfce7;color:#166534;border:1px solid #86efac}
.field-row{display:grid;grid-template-columns:1fr 1fr;gap:12px}
.field{margin-bottom:13px}
.field label{display:block;font-size:.75rem;font-weight:600;color:var(--charcoal);margin-bottom:5px;letter-spacing:.01em}
.field input{width:100%;padding:10px 13px;border-radius:9px;border:1.5px solid var(--border);font-size:.83rem;font-family:inherit;color:var(--dark);background:#fafcf8;transition:border-color .2s,box-shadow .2s;outline:none}
.field input:focus{border-color:var(--forest);box-shadow:0 0 0 3px rgba(51,106,41,.1);background:#fff}
.field .error-msg{font-size:.72rem;color:var(--red);margin-top:4px;display:block}
.pw-wrap{position:relative}
.pw-wrap input{padding-right:42px}
.pw-toggle{position:absolute;right:12px;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;color:var(--gray);padding:2px;display:flex;align-items:center;transition:color .2s}
.pw-toggle:hover{color:var(--dark)}
.form-meta{display:flex;align-items:center;justify-content:space-between;gap:12px;margin-bottom:14px}
.forgot-link{font-size:.73rem;color:var(--gray);text-decoration:none;font-weight:500;white-space:nowrap}
.forgot-link:hover{color:var(--forest);text-decoration:underline}
.btn-submit{width:100%;padding:12px;border-radius:10px;background:var(--forest);color:#fff;border:none;font-size:.88rem;font-weight:700;font-family:inherit;cursor:pointer;transition:all .2s;letter-spacing:.01em}
.btn-submit:hover{background:var(--green)}
.switch-row{text-align:center;margin-top:16px;font-size:.76rem;color:var(--gray)}
.switch-row a{color:var(--forest);font-weight:600;text-decoration:none}
.switch-row a:hover{text-decoration:underline}
.terms-note{font-size:.7rem;color:var(--gray);text-align:center;margin-top:10px;line-height:1.55}
.terms-note a{color:var(--forest)}
@media(max-width:640px){
  .nav{padding:0 18px}
  .nav-links{display:none}
  .auth-card{flex-direction:column;max-width:420px;border-radius:20px}
  .auth-left{width:100%;padding:28px 24px 20px;flex-direction:row;flex-wrap:wrap;align-items:center;gap:12px}
  .auth-left-bottom{display:none}
  .al-tagline,.al-desc,.al-features{display:none}
  .al-wordmark{font-size:1.3rem}
  .auth-tabs{padding:0 22px}
  .auth-body{padding:22px 22px 26px}
  .field-row{grid-template-columns:1fr}
}
</style>
</head>
<body>

<nav class="nav">
  <a href="{{ route('home') }}" class="nav-logo">
    <span class="brand"><span class="b">BAHAY</span>TEK</span>
    <span class="sub">Bahay Teknik</span>
  </a>
  <div class="nav-links">
    <a href="{{ route('home') }}">Home</a>
    <a href="{{ route('shop.index') }}">Shop</a>
    <a href="{{ route('workshops.index') }}">Workshops</a>
    <a href="{{ route('consultation.index') }}">Consultation</a>
  </div>
  <div class="nav-actions">
    <a href="{{ route('login') }}" class="btn-nav-outline">Log In</a>
    <a href="{{ route('register') }}" class="btn-nav-solid">Sign Up</a>
  </div>
</nav>

<div class="page-bg">
  <div class="auth-card">

    <!-- LEFT PANEL -->
    <div class="auth-left">
      <div class="auth-left-top">
        <div class="al-wordmark"><span class="b">BAHAY</span>TEK</div>
        <span class="al-sub">Bahay Teknik</span>
        <div class="al-tagline">Where <em>Ideas</em> Become<br/>Real Solutions</div>
        <p class="al-desc">Sustainable technology for agriculture, energy, and community — built in the Philippines.</p>
        <div class="al-features">
          <div class="al-feat">
            <div class="al-feat-dot"><svg viewBox="0 0 10 10" fill="none"><polyline points="1.5,5 4,7.5 8.5,2.5" stroke="#c1d95c" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg></div>
            Solar &amp; Renewable Energy
          </div>
          <div class="al-feat">
            <div class="al-feat-dot"><svg viewBox="0 0 10 10" fill="none"><polyline points="1.5,5 4,7.5 8.5,2.5" stroke="#c1d95c" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg></div>
            Biogas &amp; Clean Cooking
          </div>
          <div class="al-feat">
            <div class="al-feat-dot"><svg viewBox="0 0 10 10" fill="none"><polyline points="1.5,5 4,7.5 8.5,2.5" stroke="#c1d95c" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg></div>
            Agricultural Innovation
          </div>
          <div class="al-feat">
            <div class="al-feat-dot"><svg viewBox="0 0 10 10" fill="none"><polyline points="1.5,5 4,7.5 8.5,2.5" stroke="#c1d95c" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg></div>
            Community Workshops
          </div>
        </div>
      </div>
      <div class="auth-left-bottom">
        <svg class="al-svg" viewBox="0 0 220 120" fill="none" xmlns="http://www.w3.org/2000/svg">
          <ellipse cx="110" cy="100" rx="90" ry="18" fill="#4ade80" opacity=".12"/>
          <path d="M110 95 C110 95 85 70 85 45 C85 20 110 8 110 8 C110 8 135 20 135 45 C135 70 110 95 110 95Z" fill="#4ade80" opacity=".25"/>
          <line x1="110" y1="8" x2="110" y2="95" stroke="#86efac" stroke-width="1" opacity=".35" stroke-dasharray="3 4"/>
          <circle cx="110" cy="8" r="4" fill="#c1d95c" opacity=".5"/>
        </svg>
        <p class="al-copy">© {{ date('Y') }} Bahay Teknik · Bicol, PH</p>
      </div>
    </div>

    <!-- RIGHT PANEL -->
    <div class="auth-right">
      <div id="authView" style="display:flex;flex-direction:column;flex:1">
        <div class="auth-tabs">
          <button class="auth-tab {{ $activeTab !== 'register' ? 'active' : '' }}" id="tabLogin" onclick="switchTab('login')">Log In</button>
          <button class="auth-tab {{ $activeTab === 'register' ? 'active' : '' }}" id="tabRegister" onclick="switchTab('register')">Sign Up</button>
        </div>

        <div class="auth-body">

          <!-- LOGIN -->
          <div class="tab-panel {{ $activeTab !== 'register' ? 'active' : '' }}" id="panelLogin">
            <div class="auth-welcome">Welcome back</div>
            <div class="auth-welcome-sub">Sign in to your BAHAYTEK account</div>

            @if(session('error'))
              <div class="alert error">{{ session('error') }}</div>
            @endif

            @if($errors->any() && $activeTab !== 'register')
              <div class="alert error">
                @foreach($errors->all() as $error)
                  {{ $error }}<br>
                @endforeach
              </div>
            @endif

            <div class="divider">sign in with email</div>

            <form method="POST" action="{{ route('login') }}">
              @csrf
              <div class="field">
                <label>Email address</label>
                <input type="email" name="email" placeholder="you@example.com" value="{{ old('email') }}" required autocomplete="email"/>
              </div>
              <div class="field">
                <label>Password</label>
                <div class="pw-wrap">
                  <input type="password" name="password" id="loginPassword" placeholder="Your password" required autocomplete="current-password"/>
                  <button type="button" class="pw-toggle" onclick="togglePw('loginPassword',this)">
                    <svg id="eyeLoginOpen" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                    <svg id="eyeLoginClosed" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:none"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/></svg>
                  </button>
                </div>
              </div>
              <div class="form-meta">
                <label style="display:flex;align-items:center;gap:6px;font-size:.75rem;color:var(--gray);cursor:pointer">
                  <input type="checkbox" name="remember" style="width:auto;margin:0"> Remember me
                </label>
                <a href="#" class="forgot-link">Forgot password?</a>
              </div>
              <button type="submit" class="btn-submit">Log In</button>
            </form>

            <div class="switch-row">No account yet? <a href="#" onclick="switchTab('register');return false">Sign up free</a></div>
          </div>

          <!-- REGISTER -->
          <div class="tab-panel {{ $activeTab === 'register' ? 'active' : '' }}" id="panelRegister">
            <div class="auth-welcome">Create your account</div>
            <div class="auth-welcome-sub">Join the BAHAYTEK community</div>

            @if($errors->any() && $activeTab === 'register')
              <div class="alert error">
                @foreach($errors->all() as $error)
                  {{ $error }}<br>
                @endforeach
              </div>
            @endif

            @if(session('success'))
              <div class="alert success">{{ session('success') }}</div>
            @endif

            <div class="divider">sign up with email</div>

            <form method="POST" action="{{ route('register') }}">
              @csrf
              <div class="field-row">
                <div class="field">
                  <label>First Name</label>
                  <input type="text" name="first_name" placeholder="Juan" value="{{ old('first_name') }}" required autocomplete="given-name"/>
                </div>
                <div class="field">
                  <label>Last Name</label>
                  <input type="text" name="last_name" placeholder="dela Cruz" value="{{ old('last_name') }}" required autocomplete="family-name"/>
                </div>
              </div>
              <div class="field">
                <label>Email address</label>
                <input type="email" name="email" placeholder="you@example.com" value="{{ old('email') }}" required autocomplete="email"/>
              </div>
              <div class="field">
                <label>Phone <span style="color:var(--gray);font-weight:400;font-size:.72rem">(optional)</span></label>
                <input type="tel" name="phone" placeholder="+63 9xx xxx xxxx" value="{{ old('phone') }}" autocomplete="tel"/>
              </div>
              <div class="field">
                <label>Password <span style="color:var(--gray);font-weight:400;font-size:.72rem">(min. 8 characters)</span></label>
                <div class="pw-wrap">
                  <input type="password" name="password" id="regPassword" placeholder="Create a password" required autocomplete="new-password"/>
                  <button type="button" class="pw-toggle" onclick="togglePw('regPassword',this)">
                    <svg id="eyeRegOpen" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                    <svg id="eyeRegClosed" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:none"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/></svg>
                  </button>
                </div>
              </div>
              <div class="field">
                <label>Confirm Password</label>
                <input type="password" name="password_confirmation" placeholder="Repeat your password" required autocomplete="new-password"/>
              </div>
              <button type="submit" class="btn-submit">Create Account</button>
            </form>

            <p class="terms-note">By creating an account you agree to our <a href="#">Terms of Service</a> and <a href="#">Privacy Policy</a>.</p>
            <div class="switch-row">Already have an account? <a href="#" onclick="switchTab('login');return false">Log in</a></div>
          </div>

        </div>
      </div>
    </div>

  </div>
</div>

<script>
function switchTab(tab) {
  const isLogin = tab === 'login';
  document.getElementById('tabLogin').classList.toggle('active', isLogin);
  document.getElementById('tabRegister').classList.toggle('active', !isLogin);
  document.getElementById('panelLogin').classList.toggle('active', isLogin);
  document.getElementById('panelRegister').classList.toggle('active', !isLogin);
}
function togglePw(inputId, btn) {
  const input = document.getElementById(inputId);
  const isText = input.type === 'text';
  input.type = isText ? 'password' : 'text';
  const prefix = inputId === 'loginPassword' ? 'eyeLogin' : 'eyeReg';
  document.getElementById(prefix+'Open').style.display   = isText ? '' : 'none';
  document.getElementById(prefix+'Closed').style.display = isText ? 'none' : '';
}
</script>
</body>
</html>
