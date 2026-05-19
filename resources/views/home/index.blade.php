@extends('layouts.app')

@section('title', 'Home')

@push('styles')
<style>
/* ══ HERO ══ */
#hero{height:100vh;min-height:640px;position:relative;overflow:hidden}
.slide-wrap{position:absolute;inset:0}
.slide{position:absolute;inset:0;opacity:0;transition:opacity 1.8s cubic-bezier(.4,0,.2,1);z-index:0}
.slide.active{opacity:1;z-index:1}
.slide-bg{position:absolute;inset:0;background-size:cover;background-position:center;transform:scale(1)}
.slide.active .slide-bg{animation:kenburns 14s ease-in-out forwards}
.s2.active .slide-bg{animation:kenburns2 14s ease-in-out forwards}
.s3.active .slide-bg{animation:kenburns3 14s ease-in-out forwards}
.s4.active .slide-bg{animation:kenburns4 14s ease-in-out forwards}
@keyframes kenburns{0%{transform:scale(1) translate(0,0)}100%{transform:scale(1.12) translate(-1.5%,-1%)}}
@keyframes kenburns2{0%{transform:scale(1.04) translate(-1%,0)}100%{transform:scale(1.14) translate(1.5%,-1.5%)}}
@keyframes kenburns3{0%{transform:scale(1.02) translate(1%,0.5%)}100%{transform:scale(1.13) translate(-1%,-1%)}}
@keyframes kenburns4{0%{transform:scale(1) translate(0.5%,-0.5%)}100%{transform:scale(1.12) translate(-1.5%,0.5%)}}
.slide-overlay{position:absolute;inset:0;background:linear-gradient(to right, rgba(8,22,13,.82) 0%, rgba(10,25,15,.55) 50%, rgba(8,20,12,.22) 100%)}
.slide-overlay::after{content:'';position:absolute;inset:0;background:linear-gradient(to top,rgba(5,15,8,.75) 0%,transparent 50%)}
.s1 .slide-bg{background-image:url('/images/slides/1.jpg');background-size:cover;background-position:center}
.s2 .slide-bg{background-image:url('/images/slides/2.jpg');background-size:cover;background-position:center}
.s3 .slide-bg{background-image:url('/images/slides/3.jpg');background-size:cover;background-position:center}
.s4 .slide-bg{background-image:url('/images/slides/4.jpg');background-size:cover;background-position:center}
.hero-content{position:relative;z-index:5;display:flex;align-items:center;justify-content:space-between;height:100%;padding:0 72px 80px;gap:48px}
.hero-text-block{max-width:520px;flex:0 0 auto}
.hero-eyebrow{display:flex;align-items:center;gap:10px;margin-bottom:18px}
.hero-eyebrow-line{width:28px;height:2px;background:var(--mint);flex-shrink:0}
.hero-eyebrow-text{font-size:.62rem;font-weight:700;letter-spacing:3px;text-transform:uppercase;color:var(--mint)}
.hero-text-block h1{font-family:'DM Serif Display',serif;font-size:clamp(2.8rem,4.5vw,4.2rem);color:#fff;line-height:1.05;margin-bottom:6px;font-weight:400}
.hero-text-block h1 .h1-line2{display:block;font-style:italic;color:var(--mint)}
.hero-text-block p{color:rgba(255,255,255,.62);font-size:.92rem;line-height:1.85;max-width:440px;margin-bottom:34px;font-weight:300}
.hero-btns{display:flex;gap:14px}
.btn-slide-primary{padding:13px 28px;border-radius:10px;background:var(--mint);color:var(--forest);font-weight:800;font-size:.86rem;border:none;cursor:pointer;font-family:inherit;transition:all .2s;text-decoration:none;display:inline-flex;align-items:center}
.btn-slide-primary:hover{background:#fff;transform:translateY(-2px);box-shadow:0 12px 28px rgba(110,231,183,.25)}
.btn-slide-ghost{padding:13px 28px;border-radius:10px;background:rgba(255,255,255,.08);color:#fff;font-weight:600;font-size:.86rem;border:1.5px solid rgba(255,255,255,.28);cursor:pointer;font-family:inherit;transition:all .2s;backdrop-filter:blur(6px);text-decoration:none;display:inline-flex;align-items:center}
.btn-slide-ghost:hover{border-color:rgba(255,255,255,.65);background:rgba(255,255,255,.13)}
.hero-right{flex:0 0 380px;display:flex;flex-direction:column;gap:0}
.hero-stats-grid{background:rgba(255,255,255,.09);backdrop-filter:blur(18px);border:1px solid rgba(193,217,92,.2);border-radius:22px;padding:20px;display:grid;grid-template-columns:1fr 1fr;gap:10px}
.hsg-stat{background:rgba(255,255,255,.07);border-radius:14px;padding:20px 18px;border:1px solid rgba(255,255,255,.06);transition:background .2s;cursor:default}
.hsg-stat:hover{background:rgba(193,217,92,.12)}
.hsg-num{font-family:'DM Mono',monospace;font-size:1.7rem;font-weight:500;color:var(--mint);display:block;line-height:1}
.hsg-lbl{font-size:.65rem;font-weight:600;letter-spacing:.8px;text-transform:uppercase;color:rgba(255,255,255,.45);margin-top:5px;display:block}
.hsg-divider{grid-column:1/-1;height:1px;background:rgba(255,255,255,.07);margin:4px 0}
.hsg-links{grid-column:1/-1;display:grid;grid-template-columns:1fr 1fr;gap:10px}
.hsg-link-btn{display:flex;align-items:center;gap:10px;background:rgba(255,255,255,.07);border-radius:12px;padding:13px 15px;border:1px solid rgba(255,255,255,.06);cursor:pointer;transition:all .2s;text-decoration:none}
.hsg-link-btn:hover{background:rgba(193,217,92,.14);border-color:rgba(193,217,92,.25);transform:translateY(-2px)}
.hsg-link-icon{width:32px;height:32px;border-radius:9px;display:flex;align-items:center;justify-content:center;font-size:1rem;flex-shrink:0}
.hsg-link-txt{font-size:.78rem;font-weight:700;color:#fff}
.slide-indicators{position:absolute;bottom:32px;left:72px;z-index:6;display:flex;align-items:center;gap:12px}
.slide-dot{width:8px;height:8px;border-radius:4px;background:rgba(255,255,255,.3);cursor:pointer;transition:all .35s}
.slide-dot.active{width:26px;background:var(--mint)}

/* ══ SERVICES ══ */
#services{background:var(--white);padding:96px 72px}
.service-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:20px}
.service-card{background:var(--cream);border-radius:18px;padding:32px 26px;border:1.5px solid var(--border);transition:all .3s;position:relative;overflow:hidden}
.service-card::before{content:'';position:absolute;top:0;left:0;right:0;height:3px;border-radius:18px 18px 0 0}
.service-card:nth-child(1)::before{background:linear-gradient(90deg,var(--emerald),var(--mint))}
.service-card:nth-child(2)::before{background:linear-gradient(90deg,var(--gold),#fcd34d)}
.service-card:nth-child(3)::before{background:linear-gradient(90deg,var(--purple),#a78bfa)}
.service-card:nth-child(4)::before{background:linear-gradient(90deg,var(--blue),#60a5fa)}
.service-card:hover{transform:translateY(-6px);box-shadow:var(--sh-lg);background:var(--white);border-color:transparent}
.sc-icon{width:48px;height:48px;border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:1.3rem;margin-bottom:18px}
.sc-title{font-weight:800;font-size:.95rem;margin-bottom:8px;color:var(--dark)}
.sc-desc{font-size:.8rem;color:var(--gray);line-height:1.7}
.sc-link{font-size:.77rem;font-weight:700;color:var(--emerald);margin-top:18px;display:flex;align-items:center;gap:6px;text-decoration:none;transition:gap .2s}
.sc-link:hover{gap:10px}

/* ══ ABOUT ══ */
#about{background:linear-gradient(140deg,var(--forest) 0%,#3d7030 55%,#498428 100%);padding:80px 72px;display:flex;align-items:center;gap:72px;position:relative;overflow:hidden}
#about::before{content:'';position:absolute;top:-100px;right:-80px;width:500px;height:500px;border-radius:50%;background:radial-gradient(circle,rgba(193,217,92,.08) 0%,transparent 70%);pointer-events:none}
.about-text{flex:1;position:relative;z-index:1}
.about-eyebrow{font-size:.67rem;font-weight:700;letter-spacing:3px;text-transform:uppercase;color:var(--mint);margin-bottom:10px}
.about-text h2{font-family:'DM Serif Display',serif;font-size:2rem;color:#fff;margin-bottom:16px;line-height:1.25}
.about-text h2 em{font-style:italic;color:var(--mint)}
.about-text p{font-size:.88rem;color:rgba(255,255,255,.68);line-height:1.8;max-width:480px}
.about-badges{display:flex;gap:8px;flex-wrap:wrap;margin-top:26px}
.about-badge{padding:7px 16px;border-radius:20px;border:1.5px solid rgba(255,255,255,.2);font-size:.72rem;font-weight:600;color:rgba(255,255,255,.75);background:rgba(255,255,255,.07)}
.about-visual{flex:0 0 360px;display:grid;grid-template-columns:1fr 1fr;gap:12px;position:relative;z-index:1}
.about-card{background:rgba(255,255,255,.09);border:1px solid rgba(255,255,255,.13);border-radius:16px;padding:22px;backdrop-filter:blur(8px)}
.about-card .ico{font-size:1.5rem;margin-bottom:10px;display:block}
.about-card .ttl{font-weight:700;font-size:.85rem;color:#fff;margin-bottom:5px}
.about-card .txt{font-size:.74rem;color:rgba(255,255,255,.55);line-height:1.6}

/* ══ PREVIEW SECTIONS ══ */
.preview-section{background:var(--cream);padding:80px 72px}
.preview-section.white{background:var(--white)}
.preview-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:20px;margin-top:48px}
.preview-cta{text-align:center;margin-top:40px}
.btn-view-all{display:inline-block;padding:12px 32px;border-radius:10px;background:var(--forest);color:#fff;border:none;font-size:.88rem;font-weight:700;cursor:pointer;font-family:inherit;transition:all .2s;text-decoration:none}
.btn-view-all:hover{background:var(--emerald);transform:translateY(-2px);box-shadow:var(--sh-md)}

/* Workshop preview cards */
.workshop-card{background:var(--white);border-radius:18px;border:1.5px solid var(--border);overflow:hidden;transition:all .25s}
.workshop-card:hover{box-shadow:var(--sh-lg);transform:translateY(-4px);border-color:transparent}
.wc-top{padding:20px 20px 0;display:flex;align-items:flex-start;justify-content:space-between}
.wc-icon{width:44px;height:44px;border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:1.3rem}
.wc-badge{font-size:.63rem;font-weight:800;text-transform:uppercase;letter-spacing:.8px;padding:5px 10px;border-radius:20px}
.badge-open{background:rgba(16,185,129,.1);color:#059669}
.badge-soon{background:rgba(251,191,36,.12);color:#b45309}
.wc-body{padding:14px 20px 20px}
.wc-title{font-weight:800;font-size:.92rem;margin-bottom:7px;color:var(--dark)}
.wc-desc{font-size:.77rem;color:var(--gray);line-height:1.6}

/* Product preview cards */
.product-card{background:var(--white);border-radius:16px;border:1.5px solid var(--border);overflow:hidden;transition:all .25s}
.product-card:hover{transform:translateY(-5px);box-shadow:var(--sh-lg);border-color:transparent}
.product-img{height:148px;display:flex;align-items:center;justify-content:center;font-size:3rem;background:var(--cream)}
.product-body{padding:16px}
.product-category{font-size:.62rem;font-weight:700;text-transform:uppercase;letter-spacing:1.2px;color:var(--emerald);margin-bottom:5px}
.product-name{font-weight:700;font-size:.88rem;margin-bottom:5px;color:var(--dark)}
.product-price{font-family:'DM Mono',monospace;font-size:1rem;font-weight:500;color:var(--forest)}

/* ══ REVEAL ══ */
.reveal{opacity:0;transform:translateY(26px);transition:opacity .6s,transform .6s}
.reveal.visible{opacity:1;transform:translateY(0)}
.rd1{transition-delay:.1s}.rd2{transition-delay:.2s}.rd3{transition-delay:.3s}

@media(max-width:1100px){
  .service-grid{grid-template-columns:repeat(2,1fr)}
  .preview-grid{grid-template-columns:repeat(2,1fr)}
  #services,.preview-section{padding:80px 48px}
  #about{padding:64px 48px;flex-direction:column}
  .about-visual{flex:unset;width:100%}
  .hero-content{padding:0 48px 80px}
  .slide-indicators{left:48px}
}
@media(max-width:768px){
  .hero-content{padding:0 24px 80px;flex-direction:column;justify-content:flex-end;padding-bottom:120px}
  .hero-right{display:none}
  .slide-indicators{left:24px}
  #services,.preview-section{padding:64px 24px}
  .service-grid,.preview-grid{grid-template-columns:1fr}
  #about{padding:64px 24px;gap:36px}
}
</style>
@endpush

@section('content')
<section id="hero" style="padding-top:62px">
  <div class="slide-wrap">
    <div class="slide s1 active" id="slide0">
      <div class="slide-bg"></div>
      <div class="slide-overlay"></div>
    </div>
    <div class="slide s2" id="slide1">
      <div class="slide-bg"></div>
      <div class="slide-overlay"></div>
    </div>
    <div class="slide s3" id="slide2">
      <div class="slide-bg"></div>
      <div class="slide-overlay"></div>
    </div>
    <div class="slide s4" id="slide3">
      <div class="slide-bg"></div>
      <div class="slide-overlay"></div>
    </div>
  </div>

  <div class="hero-content">
    <div class="hero-text-block">
      <div class="hero-eyebrow">
        <div class="hero-eyebrow-line"></div>
        <span class="hero-eyebrow-text">Sustainable Livelihood Technology</span>
      </div>
      <h1 id="heroH1">
        Empowering Communities
        <span class="h1-line2">Through Innovation</span>
      </h1>
      <p id="heroP">From biogas to solar to precision agriculture — BAHAYTEK delivers hands-on technology that transforms rural livelihoods in the Philippines.</p>
      <div class="hero-btns">
        <a href="{{ route('consultation.index') }}" class="btn-slide-primary">Book a Consultation</a>
        <a href="{{ route('services') }}" class="btn-slide-ghost">Explore Services</a>
      </div>
    </div>

    <div class="hero-right">
      <div class="hero-stats-grid">
        <div class="hsg-stat">
          <span class="hsg-num">120+</span>
          <span class="hsg-lbl">Projects Completed</span>
        </div>
        <div class="hsg-stat">
          <span class="hsg-num">480+</span>
          <span class="hsg-lbl">Graduates Trained</span>
        </div>
        <div class="hsg-stat">
          <span class="hsg-num">8</span>
          <span class="hsg-lbl">Expert Trainers</span>
        </div>
        <div class="hsg-stat">
          <span class="hsg-num">12</span>
          <span class="hsg-lbl">Active Programs</span>
        </div>
        <div class="hsg-divider"></div>
        <div class="hsg-links">
          <a href="{{ route('shop.index') }}" class="hsg-link-btn">
            <div class="hsg-link-icon" style="background:rgba(193,217,92,.15)">🛒</div>
            <span class="hsg-link-txt">Shop Products</span>
          </a>
          <a href="{{ route('workshops.index') }}" class="hsg-link-btn">
            <div class="hsg-link-icon" style="background:rgba(193,217,92,.15)">🎓</div>
            <span class="hsg-link-txt">Join Workshops</span>
          </a>
        </div>
      </div>
    </div>
  </div>

  <div class="slide-indicators" id="slideIndicators">
    <div class="slide-dot active" onclick="goSlide(0)"></div>
    <div class="slide-dot" onclick="goSlide(1)"></div>
    <div class="slide-dot" onclick="goSlide(2)"></div>
    <div class="slide-dot" onclick="goSlide(3)"></div>
  </div>
</section>

<!-- SERVICES -->
<section id="services">
  <div class="section-eyebrow">What We Offer</div>
  <h2 class="section-heading">Integrated <em>Solutions</em> for Communities</h2>
  <p class="section-sub">From research and consultancy to hands-on product development and training — we cover the full livelihood technology cycle.</p>
  <div class="service-grid">
    <div class="service-card reveal rd1">
      <div class="sc-icon" style="background:rgba(128,177,85,.12)">🔬</div>
      <div class="sc-title">Research & Development</div>
      <p class="sc-desc">Evidence-based solutions tailored to local agricultural and energy challenges in rural Philippine communities.</p>
      <a href="{{ route('services') }}" class="sc-link">Learn more →</a>
    </div>
    <div class="service-card reveal rd2">
      <div class="sc-icon" style="background:rgba(182,138,59,.12)">💡</div>
      <div class="sc-title">Consultancy Services</div>
      <p class="sc-desc">Expert guidance for biogas, solar, water management, and agri-technology implementation projects.</p>
      <a href="{{ route('consultation.index') }}" class="sc-link">Book a session →</a>
    </div>
    <div class="service-card reveal rd3">
      <div class="sc-icon" style="background:rgba(109,40,217,.1)">⚙️</div>
      <div class="sc-title">Product Development</div>
      <p class="sc-desc">From prototype to market-ready — we co-develop sustainable technology products with local partners.</p>
      <a href="{{ route('shop.index') }}" class="sc-link">Browse products →</a>
    </div>
    <div class="service-card reveal rd3">
      <div class="sc-icon" style="background:rgba(29,78,216,.1)">🎓</div>
      <div class="sc-title">Training & Workshops</div>
      <p class="sc-desc">Hands-on training programs that build local capacity in sustainable technology operation and maintenance.</p>
      <a href="{{ route('workshops.index') }}" class="sc-link">View programs →</a>
    </div>
  </div>
</section>

<!-- ABOUT -->
<section id="about">
  <div class="about-text reveal">
    <div class="about-eyebrow">Who We Are</div>
    <h2>Bahay Teknik — <em>Technology for Every Home</em></h2>
    <p>BAHAYTEK is a Philippine-based sustainable livelihood technology center dedicated to making clean energy, innovative agriculture, and modern water management accessible to every community. We bridge research and real-world application through workshops, consultancy, and locally-crafted solutions.</p>
    <div class="about-badges">
      <span class="about-badge">Bicol, Philippines</span>
      <span class="about-badge">Since 2018</span>
      <span class="about-badge">DOST-Accredited</span>
      <span class="about-badge">ISO Certified</span>
    </div>
  </div>
  <div class="about-visual">
    <div class="about-card reveal rd1">
      <span class="ico">☀️</span>
      <div class="ttl">Solar & Renewable</div>
      <p class="txt">Solar panel installation and off-grid energy systems for rural households.</p>
    </div>
    <div class="about-card reveal rd2">
      <span class="ico">🔥</span>
      <div class="ttl">Biogas Systems</div>
      <p class="txt">Clean cooking fuel from agricultural waste — reducing cost and deforestation.</p>
    </div>
    <div class="about-card reveal rd3">
      <span class="ico">🌾</span>
      <div class="ttl">Precision Agriculture</div>
      <p class="txt">Smart irrigation and soil monitoring tools for higher crop yields.</p>
    </div>
    <div class="about-card reveal rd3">
      <span class="ico">💧</span>
      <div class="ttl">Water Management</div>
      <p class="txt">Rainwater harvesting and gravity-fed irrigation infrastructure.</p>
    </div>
  </div>
</section>

<!-- WORKSHOP PREVIEW -->
<section class="preview-section">
  <div class="section-eyebrow">Training Programs</div>
  <h2 class="section-heading">Learn <em>Sustainable Technology</em></h2>
  <p class="section-sub">Hands-on workshops open to farmers, entrepreneurs, and community leaders. Earn certifications and gain practical skills.</p>
  <div class="preview-grid">
    <div class="workshop-card reveal rd1">
      <div class="wc-top">
        <div class="wc-icon" style="background:rgba(128,177,85,.12)">☀️</div>
        <span class="wc-badge badge-open">Open</span>
      </div>
      <div class="wc-body">
        <div class="wc-title">Solar Panel Installation & Maintenance</div>
        <p class="wc-desc">Learn to install, wire, and maintain residential and small commercial solar PV systems.</p>
      </div>
    </div>
    <div class="workshop-card reveal rd2">
      <div class="wc-top">
        <div class="wc-icon" style="background:rgba(182,138,59,.12)">🔥</div>
        <span class="wc-badge badge-open">Open</span>
      </div>
      <div class="wc-body">
        <div class="wc-title">Biogas Digester Construction</div>
        <p class="wc-desc">Build and operate biogas digesters using agricultural waste for clean household energy.</p>
      </div>
    </div>
    <div class="workshop-card reveal rd3">
      <div class="wc-top">
        <div class="wc-icon" style="background:rgba(29,78,216,.1)">💧</div>
        <span class="wc-badge badge-soon">Coming Soon</span>
      </div>
      <div class="wc-body">
        <div class="wc-title">Rainwater Harvesting Systems</div>
        <p class="wc-desc">Design and install gravity-fed water collection and distribution systems for farms.</p>
      </div>
    </div>
  </div>
  <div class="preview-cta">
    <a href="{{ route('workshops.index') }}" class="btn-view-all">View All Workshops</a>
  </div>
</section>

<!-- SHOP PREVIEW -->
<section class="preview-section white">
  <div class="section-eyebrow">Our Products</div>
  <h2 class="section-heading">Sustainable Tech, <em>Ready to Deploy</em></h2>
  <p class="section-sub">Shop our range of BAHAYTEK-developed technology products — from solar kits to biogas accessories to smart irrigation tools.</p>
  <div class="preview-grid">
    <div class="product-card reveal rd1">
      <div class="product-img">☀️</div>
      <div class="product-body">
        <div class="product-category">Solar Energy</div>
        <div class="product-name">100W Solar Panel Kit</div>
        <div class="product-price">₱4,500</div>
      </div>
    </div>
    <div class="product-card reveal rd2">
      <div class="product-img">🔥</div>
      <div class="product-body">
        <div class="product-category">Biogas</div>
        <div class="product-name">Biogas Burner Stove</div>
        <div class="product-price">₱1,200</div>
      </div>
    </div>
    <div class="product-card reveal rd3">
      <div class="product-img">💧</div>
      <div class="product-body">
        <div class="product-category">Water Systems</div>
        <div class="product-name">Drip Irrigation Starter Kit</div>
        <div class="product-price">₱2,800</div>
      </div>
    </div>
  </div>
  <div class="preview-cta">
    <a href="{{ route('shop.index') }}" class="btn-view-all">Shop All Products</a>
  </div>
</section>
@endsection

@push('scripts')
<script>
const slides     = document.querySelectorAll('.slide');
const dots       = document.querySelectorAll('.slide-dot');
const heroTexts  = [
  { h1: 'Empowering Communities<span class="h1-line2">Through Innovation</span>',
    p:  'From biogas to solar to precision agriculture — BAHAYTEK delivers hands-on technology that transforms rural livelihoods in the Philippines.' },
  { h1: 'Clean Energy for<span class="h1-line2">Every Household</span>',
    p:  'Biogas digesters, solar panels, and renewable energy systems built and deployed by BAHAYTEK-certified technicians.' },
  { h1: 'Harvest More with<span class="h1-line2">Smart Farming</span>',
    p:  'Precision agriculture tools, smart irrigation, and soil analytics to boost yields and reduce water waste.' },
  { h1: 'Water Management<span class="h1-line2">for Rural Communities</span>',
    p:  'Gravity-fed irrigation and rainwater harvesting systems that work where the grid cannot reach.' },
];
let cur = 0, timer;
function goSlide(n) {
  slides[cur].classList.remove('active');
  dots[cur].classList.remove('active');
  cur = n;
  slides[cur].classList.add('active');
  dots[cur].classList.add('active');
  document.getElementById('heroH1').innerHTML = heroTexts[cur].h1;
  document.getElementById('heroP').textContent = heroTexts[cur].p;
  clearInterval(timer);
  timer = setInterval(nextSlide, 6000);
}
function nextSlide() { goSlide((cur + 1) % slides.length); }
timer = setInterval(nextSlide, 6000);

// Scroll reveal
const revealEls = document.querySelectorAll('.reveal');
const io = new IntersectionObserver((entries) => {
  entries.forEach(e => { if (e.isIntersecting) { e.target.classList.add('visible'); io.unobserve(e.target); } });
}, { threshold: 0.1 });
revealEls.forEach(el => io.observe(el));
</script>
@endpush
