<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title>Admin Login — BAHAYTEK</title>
<meta name="csrf-token" content="{{ csrf_token() }}">
<link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&family=DM+Sans:wght@300;400;500;600;700&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet"/>
<style>
:root{
  --forest:#336a29;--green:#498428;--emerald:#80b155;--mint:#c1d95c;
  --cream:#f5f9f0;--dark:#1a2e12;--charcoal:#2e4a1e;--gray:#5a7248;--border:#c8ddb0;
  --red:#dc2626;--red-lt:#fee2e2;--white:#ffffff;
  --sh-lg:0 24px 64px rgba(51,106,41,.18);
}
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
body{font-family:'DM Sans',sans-serif;background:var(--dark);color:var(--dark);min-height:100vh;display:flex;align-items:center;justify-content:center}
.admin-login-wrap{width:100%;max-width:400px;padding:24px}
.admin-card{background:#fff;border-radius:20px;box-shadow:var(--sh-lg);overflow:hidden}
.admin-card-header{background:linear-gradient(160deg,#1e4d18 0%,#336a29 100%);padding:32px 32px 28px;text-align:center}
.admin-wordmark{font-family:'DM Serif Display',serif;font-size:1.8rem;color:#fff;letter-spacing:.5px}
.admin-wordmark .b{color:var(--mint)}
.admin-sub{font-size:.55rem;font-weight:600;letter-spacing:3px;text-transform:uppercase;color:rgba(193,217,92,.6);margin-top:4px;display:block}
.admin-badge{display:inline-block;margin-top:12px;padding:4px 14px;background:rgba(193,217,92,.15);border:1px solid rgba(193,217,92,.3);border-radius:20px;font-size:.7rem;font-weight:700;color:var(--mint);letter-spacing:1px;text-transform:uppercase}
.admin-card-body{padding:28px 32px 32px}
.admin-title{font-size:1rem;font-weight:700;color:var(--dark);margin-bottom:4px}
.admin-sub-title{font-size:.78rem;color:var(--gray);margin-bottom:22px}
.alert{padding:10px 14px;border-radius:9px;font-size:.78rem;font-weight:500;margin-bottom:14px;line-height:1.4}
.alert.error{background:var(--red-lt);color:var(--red);border:1px solid #fca5a5}
.field{margin-bottom:14px}
.field label{display:block;font-size:.75rem;font-weight:600;color:var(--charcoal);margin-bottom:5px}
.field input{width:100%;padding:10px 13px;border-radius:9px;border:1.5px solid var(--border);font-size:.83rem;font-family:inherit;color:var(--dark);background:#fafcf8;transition:border-color .2s;outline:none}
.field input:focus{border-color:var(--forest);box-shadow:0 0 0 3px rgba(51,106,41,.1);background:#fff}
.pw-wrap{position:relative}
.pw-wrap input{padding-right:42px}
.pw-toggle{position:absolute;right:12px;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;color:var(--gray);padding:2px;display:flex}
.btn-submit{width:100%;padding:12px;border-radius:10px;background:var(--forest);color:#fff;border:none;font-size:.88rem;font-weight:700;font-family:inherit;cursor:pointer;transition:all .2s;margin-top:4px}
.btn-submit:hover{background:var(--green)}
.back-link{display:block;text-align:center;margin-top:18px;font-size:.75rem;color:var(--gray);text-decoration:none}
.back-link:hover{color:var(--forest)}
</style>
</head>
<body>

<div class="admin-login-wrap">
  <div class="admin-card">
    <div class="admin-card-header">
      <div class="admin-wordmark"><span class="b">BAHAY</span>TEK</div>
      <span class="admin-sub">Bahay Teknik</span>
      <div class="admin-badge">Admin Portal</div>
    </div>
    <div class="admin-card-body">
      <div class="admin-title">Staff Sign In</div>
      <div class="admin-sub-title">Enter your credentials to access the admin panel</div>

      @if(session('error'))
        <div class="alert error">{{ session('error') }}</div>
      @endif
      @if($errors->any())
        <div class="alert error">
          @foreach($errors->all() as $error)
            {{ $error }}<br>
          @endforeach
        </div>
      @endif

      <form method="POST" action="{{ route('admin.login') }}">
        @csrf
        <div class="field">
          <label>Email address</label>
          <input type="email" name="email" placeholder="staff@bahaytek.com" value="{{ old('email') }}" required autocomplete="email"/>
        </div>
        <div class="field">
          <label>Password</label>
          <div class="pw-wrap">
            <input type="password" name="password" id="pw" placeholder="Your password" required autocomplete="current-password"/>
            <button type="button" class="pw-toggle" onclick="togglePw()">
              <svg id="eyeOpen" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
              <svg id="eyeClosed" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:none"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/></svg>
            </button>
          </div>
        </div>
        <button type="submit" class="btn-submit">Sign In to Admin</button>
      </form>

      <a href="{{ route('home') }}" class="back-link">← Back to main site</a>
    </div>
  </div>
</div>

<script>
function togglePw() {
  const pw = document.getElementById('pw');
  const isText = pw.type === 'text';
  pw.type = isText ? 'password' : 'text';
  document.getElementById('eyeOpen').style.display   = isText ? '' : 'none';
  document.getElementById('eyeClosed').style.display = isText ? 'none' : '';
}
</script>
</body>
</html>
