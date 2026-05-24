@extends('layouts.app')
@section('title','Book a Consultation')

@push('styles')
<style>
.page-hero{background:linear-gradient(135deg,#1a2e12 0%,#2a4a1e 50%,#336a29 100%);padding:80px 0 48px;position:relative;overflow:hidden}
.page-hero::before{content:'';position:absolute;inset:0;background:radial-gradient(ellipse 60% 70% at 80% 50%,rgba(193,217,92,.06) 0%,transparent 60%)}
.hero-inner{max-width:960px;margin:0 auto;padding:0 48px;position:relative;z-index:1}
.hero-eyebrow{font-size:.65rem;font-weight:700;letter-spacing:3px;text-transform:uppercase;color:var(--mint);margin-bottom:10px;display:flex;align-items:center;gap:10px}
.hero-eyebrow::before{content:'';width:20px;height:2px;background:var(--mint);flex-shrink:0}
.hero-title{font-family:'DM Serif Display',serif;font-size:2.4rem;color:#fff;margin-bottom:12px;line-height:1.2}
.hero-title em{font-style:italic;color:var(--mint)}
.hero-sub{color:rgba(255,255,255,.6);font-size:.9rem;line-height:1.7;max-width:520px}

.booking-wrap{max-width:960px;margin:0 auto;padding:36px 48px;display:grid;grid-template-columns:1fr 300px;gap:24px;align-items:start}

/* STEP CARDS */
.step-card{background:#fff;border:1px solid var(--border);border-radius:16px;box-shadow:var(--sh-sm);margin-bottom:18px;overflow:hidden}
.step-header{padding:16px 22px;border-bottom:1px solid var(--border);display:flex;align-items:center;gap:12px}
.step-num{width:26px;height:26px;border-radius:50%;background:var(--forest);color:#fff;font-size:.72rem;font-weight:700;display:flex;align-items:center;justify-content:center;flex-shrink:0}
.step-label{font-size:.88rem;font-weight:700;color:var(--dark)}
.step-body{padding:20px 22px}

/* SERVICE CARDS */
.service-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:10px}
.service-card{border:2px solid var(--border);border-radius:12px;padding:14px;cursor:pointer;transition:all .2s;position:relative;background:#fff}
.service-card:hover{border-color:var(--emerald);background:var(--sand)}
.service-card.selected{border-color:var(--forest);background:var(--sand)}
.service-card input[type=radio]{position:absolute;opacity:0;width:0;height:0}
.service-icon{width:32px;height:32px;border-radius:8px;background:var(--sand);display:flex;align-items:center;justify-content:center;margin-bottom:8px}
.service-card.selected .service-icon{background:rgba(51,106,41,.12)}
.service-name{font-size:.8rem;font-weight:700;color:var(--dark);margin-bottom:3px}
.service-desc{font-size:.7rem;color:var(--gray);line-height:1.5}
.service-check{position:absolute;top:10px;right:10px;width:16px;height:16px;border-radius:50%;border:2px solid var(--border);background:#fff;display:flex;align-items:center;justify-content:center;transition:all .2s}
.service-card.selected .service-check{background:var(--forest);border-color:var(--forest)}
.service-check svg{display:none}
.service-card.selected .service-check svg{display:block}

/* CALENDAR */
.cal-header{display:flex;align-items:center;justify-content:space-between;margin-bottom:14px}
.cal-month{font-size:.92rem;font-weight:700;color:var(--dark)}
.cal-nav{background:none;border:1.5px solid var(--border);border-radius:8px;width:30px;height:30px;cursor:pointer;display:flex;align-items:center;justify-content:center;color:var(--gray);transition:all .2s}
.cal-nav:hover{border-color:var(--forest);color:var(--forest)}
.cal-grid{display:grid;grid-template-columns:repeat(7,1fr);gap:3px}
.cal-dow{text-align:center;font-size:.62rem;font-weight:700;color:var(--gray);text-transform:uppercase;letter-spacing:.5px;padding:4px 0}
.cal-day{text-align:center;padding:7px 2px;border-radius:8px;font-size:.78rem;cursor:pointer;transition:all .2s;border:1.5px solid transparent;line-height:1}
.cal-day:hover:not(.empty):not(.past):not(.weekend){border-color:var(--emerald);background:var(--sand)}
.cal-day.selected{background:var(--forest);color:#fff;border-color:var(--forest);font-weight:700}
.cal-day.past,.cal-day.weekend{color:#c8d8b8;cursor:not-allowed}
.cal-day.empty{cursor:default}

/* SLOTS */
.slots-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:8px}
.slot-btn{padding:10px 6px;border-radius:9px;border:1.5px solid var(--border);background:#fff;font-size:.77rem;font-weight:600;cursor:pointer;transition:all .2s;font-family:inherit;color:var(--dark);text-align:center;line-height:1.3}
.slot-btn .slot-type{font-size:.64rem;font-weight:400;color:var(--gray);margin-top:2px;display:block}
.slot-btn:hover:not(:disabled){border-color:var(--emerald);background:var(--sand)}
.slot-btn.selected{background:var(--forest);color:#fff;border-color:var(--forest)}
.slot-btn.selected .slot-type{color:rgba(255,255,255,.7)}
.slot-btn:disabled{opacity:.4;cursor:not-allowed}
.slots-msg{color:var(--gray);font-size:.82rem;padding:8px 0;font-style:italic}

/* FORM */
.form-row{display:grid;grid-template-columns:1fr 1fr;gap:12px}
.field{margin-bottom:12px}
.field label{display:block;font-size:.75rem;font-weight:600;color:var(--charcoal);margin-bottom:4px}
.field input,.field textarea,.field select{width:100%;padding:9px 12px;border-radius:9px;border:1.5px solid var(--border);font-size:.82rem;font-family:inherit;color:var(--dark);background:#fafcf8;transition:border-color .2s;outline:none}
.field input:focus,.field textarea:focus,.field select:focus{border-color:var(--forest);box-shadow:0 0 0 3px rgba(51,106,41,.08);background:#fff}
.field textarea{resize:vertical;min-height:80px}
.collab-toggle{display:flex;align-items:center;gap:10px;padding:12px 14px;border-radius:10px;border:1.5px solid var(--border);cursor:pointer;background:#fafcf8;transition:all .2s;margin-bottom:12px}
.collab-toggle:hover{border-color:var(--emerald);background:var(--sand)}
.collab-toggle input[type=checkbox]{width:15px;height:15px;accent-color:var(--forest)}
.collab-label{font-size:.8rem;font-weight:600;color:var(--dark)}
.collab-label span{display:block;font-size:.7rem;font-weight:400;color:var(--gray);margin-top:1px}
.collab-fields{background:var(--sand);border-radius:10px;padding:14px;border:1px solid var(--border);margin-bottom:12px;display:none}
.collab-fields.show{display:block}
.btn-book{width:100%;padding:13px;border-radius:12px;background:var(--forest);color:#fff;border:none;font-size:.9rem;font-weight:700;font-family:inherit;cursor:pointer;transition:all .2s}
.btn-book:hover{background:var(--green)}

/* SIDEBAR */
.summary-card{background:linear-gradient(160deg,#1a2e12 0%,#2d5020 60%,#336a29 100%);border-radius:16px;padding:22px;color:#fff;position:sticky;top:82px}
.sum-title{font-family:'DM Serif Display',serif;font-size:.95rem;color:#fff;margin-bottom:14px;display:flex;align-items:center;gap:8px}
.sum-title::after{content:'';flex:1;height:1px;background:rgba(193,217,92,.2)}
.sum-item{margin-bottom:10px}
.sum-key{font-size:.62rem;font-weight:700;letter-spacing:1.5px;text-transform:uppercase;color:rgba(193,217,92,.6);margin-bottom:3px}
.sum-val{font-size:.82rem;font-weight:600;color:#fff}
.sum-empty{color:rgba(255,255,255,.35);font-style:italic;font-weight:400}
.sum-divider{height:1px;background:rgba(193,217,92,.15);margin:14px 0}
.sum-note{font-size:.7rem;color:rgba(255,255,255,.45);line-height:1.6}
.my-bookings-link{display:flex;align-items:center;gap:6px;margin-top:14px;padding:8px 12px;border-radius:9px;background:rgba(193,217,92,.1);border:1px solid rgba(193,217,92,.2);color:var(--mint);font-size:.73rem;font-weight:600;text-decoration:none;transition:all .2s}
.my-bookings-link:hover{background:rgba(193,217,92,.18);color:#fff}

.error-box{background:var(--red-lt);color:var(--red);border:1px solid #fca5a5;border-radius:10px;padding:12px 16px;font-size:.82rem;margin-bottom:18px}
.info-box{background:var(--sand);border:1.5px solid var(--border);border-radius:12px;padding:14px 18px;margin-bottom:18px;font-size:.83rem;color:var(--charcoal);display:flex;align-items:center;gap:12px}

@media(max-width:880px){
  .booking-wrap{grid-template-columns:1fr;padding:20px 18px}
  .service-grid{grid-template-columns:1fr}
  .booking-sidebar{order:-1}
  .summary-card{position:static}
  .slots-grid{grid-template-columns:repeat(2,1fr)}
  .form-row{grid-template-columns:1fr}
}
</style>
@endpush

@section('content')
<div class="page-hero">
  <div class="hero-inner">
    <div class="hero-eyebrow">Consultation</div>
    <h1 class="hero-title">Book a <em>Consultation</em></h1>
    <p class="hero-sub">Schedule a one-on-one session with our research and technology experts. Browse available slots and confirm your booking.</p>
  </div>
</div>

<div class="booking-wrap">
  <div class="booking-main">

    @if($errors->any())
    <div class="error-box">
      @foreach($errors->all() as $e)<div>{{ $e }}</div>@endforeach
    </div>
    @endif

    @if(!auth()->check())
    <div class="info-box">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="var(--forest)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
      <span>You need to <a href="{{ route('login') }}" style="color:var(--forest);font-weight:700">log in</a> or <a href="{{ route('register') }}" style="color:var(--forest);font-weight:700">create an account</a> to complete a booking.</span>
    </div>
    @endif

    <form method="POST" action="{{ route('consultation.book') }}" id="bookingForm">
    @csrf
    <input type="hidden" name="schedule_id" id="scheduleId" value="{{ old('schedule_id') }}">

    <!-- STEP 1: Date & Time Slot -->
    <div class="step-card">
      <div class="step-header">
        <div class="step-num">1</div>
        <div class="step-label">Choose a Date</div>
      </div>
      <div class="step-body">
        <div class="cal-header">
          <button type="button" class="cal-nav" onclick="changeMonth(-1)">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg>
          </button>
          <div class="cal-month" id="calMonth"></div>
          <button type="button" class="cal-nav" onclick="changeMonth(1)">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
          </button>
        </div>
        <div class="cal-grid" id="calGrid"></div>
      </div>
    </div>

    <!-- STEP 2: Select Slot -->
    <div class="step-card">
      <div class="step-header">
        <div class="step-num">2</div>
        <div class="step-label">Select a Time Slot</div>
      </div>
      <div class="step-body">
        <div id="slotsLabel" style="font-size:.78rem;color:var(--gray);margin-bottom:10px;font-style:italic">Select a date above to see available slots.</div>
        <div id="slotsContainer"></div>
      </div>
    </div>

    <!-- STEP 3: Your Details -->
    <div class="step-card">
      <div class="step-header">
        <div class="step-num">3</div>
        <div class="step-label">Your Details</div>
      </div>
      <div class="step-body">
        <div class="form-row">
          <div class="field">
            <label>First Name <span style="color:var(--red)">*</span></label>
            <input type="text" name="first_name" placeholder="Juan" value="{{ old('first_name', auth()->user()?->first_name) }}" required>
          </div>
          <div class="field">
            <label>Last Name <span style="color:var(--red)">*</span></label>
            <input type="text" name="last_name" placeholder="dela Cruz" value="{{ old('last_name', auth()->user()?->last_name) }}" required>
          </div>
        </div>
        <div class="form-row">
          <div class="field">
            <label>Email Address <span style="color:var(--red)">*</span></label>
            <input type="email" name="email" placeholder="you@example.com" value="{{ old('email', auth()->user()?->email) }}" required>
          </div>
          <div class="field">
            <label>Phone Number</label>
            <input type="tel" name="phone" placeholder="+63 9xx xxx xxxx" value="{{ old('phone', auth()->user()?->phone) }}">
          </div>
        </div>
        <div class="form-row">
          <div class="field">
            <label>Organization / Institution</label>
            <input type="text" name="organization" placeholder="Company or university" value="{{ old('organization') }}">
          </div>
          <div class="field">
            <label>Number of Attendees <span style="color:var(--red)">*</span></label>
            <input type="number" name="no_attendees" min="1" max="50" value="{{ old('no_attendees', 1) }}" required>
          </div>
        </div>
      </div>
    </div>

    <!-- STEP 4: Topic -->
    <div class="step-card">
      <div class="step-header">
        <div class="step-num">4</div>
        <div class="step-label">Consultation Topic</div>
      </div>
      <div class="step-body">
        <div class="field">
          <label>Topic / Subject <span style="color:var(--red)">*</span></label>
          <input type="text" name="topic" placeholder="e.g. Biogas system feasibility for small farms" value="{{ old('topic') }}" required>
        </div>
        <div class="field">
          <label>Brief Description</label>
          <textarea name="description" placeholder="Describe what you'd like to discuss or any specific questions...">{{ old('description') }}</textarea>
        </div>

        <label class="collab-toggle">
          <input type="checkbox" name="is_research_collab" id="collabCheck" value="1" {{ old('is_research_collab') ? 'checked' : '' }} onchange="toggleCollab()">
          <div>
            <div class="collab-label">Research Collaboration
              <span>Check if seeking a formal research partnership or collaboration agreement.</span>
            </div>
          </div>
        </label>

        <div class="collab-fields {{ old('is_research_collab') ? 'show' : '' }}" id="collabFields">
          <div class="form-row">
            <div class="field" style="margin-bottom:0">
              <label>Partner Institution</label>
              <input type="text" name="collab_institution" placeholder="University, agency, or organization" value="{{ old('collab_institution') }}">
            </div>
            <div class="field" style="margin-bottom:0">
              <label>Collaboration Type</label>
              <select name="collab_type">
                <option value="">Select type...</option>
                <option value="joint_research"       {{ old('collab_type')==='joint_research'?'selected':'' }}>Joint Research</option>
                <option value="technology_transfer"  {{ old('collab_type')==='technology_transfer'?'selected':'' }}>Technology Transfer</option>
                <option value="funding_partnership"  {{ old('collab_type')==='funding_partnership'?'selected':'' }}>Funding Partnership</option>
                <option value="publication"          {{ old('collab_type')==='publication'?'selected':'' }}>Co-Publication</option>
                <option value="other"                {{ old('collab_type')==='other'?'selected':'' }}>Other</option>
              </select>
            </div>
          </div>
        </div>

        @auth
          <button type="submit" class="btn-book">Confirm Booking Request</button>
        @else
          <a href="{{ route('login') }}" class="btn-book" style="display:block;text-align:center;text-decoration:none;line-height:1.5">Log In to Book</a>
        @endauth
      </div>
    </div>

    </form>
  </div>

  <!-- SIDEBAR -->
  <div class="booking-sidebar">
    <div class="summary-card">
      <div class="sum-title">Booking Summary</div>
      <div class="sum-item">
        <div class="sum-key">Date</div>
        <div class="sum-val" id="sumDate"><span class="sum-empty">Not selected</span></div>
      </div>
      <div class="sum-item">
        <div class="sum-key">Time</div>
        <div class="sum-val" id="sumTime"><span class="sum-empty">Not selected</span></div>
      </div>
      <div class="sum-item">
        <div class="sum-key">Session Type</div>
        <div class="sum-val" id="sumType"><span class="sum-empty">—</span></div>
      </div>
      <div class="sum-divider"></div>
      <div class="sum-note">Once submitted, our team will review your request and confirm within 24 hours via email.</div>
      @auth
        <a href="{{ route('consultation.my-bookings') }}" class="my-bookings-link">
          <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
          View My Bookings
        </a>
      @endauth
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
const TODAY = new Date(); TODAY.setHours(0,0,0,0);
let curYear = TODAY.getFullYear(), curMonth = TODAY.getMonth();
let selectedDate = null, selectedSlot = null;
const MONTHS = ['January','February','March','April','May','June','July','August','September','October','November','December'];
const DAYS   = ['Sun','Mon','Tue','Wed','Thu','Fri','Sat'];

function pad(n){ return String(n).padStart(2,'0'); }

function renderCalendar(){
  document.getElementById('calMonth').textContent = MONTHS[curMonth]+' '+curYear;
  const grid = document.getElementById('calGrid');
  grid.innerHTML='';
  DAYS.forEach(d=>{ const el=document.createElement('div'); el.className='cal-dow'; el.textContent=d; grid.appendChild(el); });
  const first = new Date(curYear,curMonth,1).getDay();
  const days  = new Date(curYear,curMonth+1,0).getDate();
  const minDate = new Date(TODAY); minDate.setDate(minDate.getDate()+1);
  for(let i=0;i<first;i++){ const el=document.createElement('div'); el.className='cal-day empty'; grid.appendChild(el); }
  for(let d=1;d<=days;d++){
    const date=new Date(curYear,curMonth,d);
    const iso=curYear+'-'+pad(curMonth+1)+'-'+pad(d);
    const el=document.createElement('div'); el.textContent=d; el.className='cal-day';
    if(date<minDate||date.getDay()===0||date.getDay()===6){ el.classList.add(date.getDay()===0||date.getDay()===6?'weekend':'past'); }
    else { if(selectedDate===iso) el.classList.add('selected'); el.addEventListener('click',()=>pickDate(iso,date,el)); }
    grid.appendChild(el);
  }
}

function pickDate(iso,dateObj,el){
  document.querySelectorAll('.cal-day.selected').forEach(e=>e.classList.remove('selected'));
  el.classList.add('selected');
  selectedDate=iso; selectedSlot=null;
  document.getElementById('scheduleId').value='';
  document.getElementById('sumDate').textContent=dateObj.toLocaleDateString('en-PH',{weekday:'long',year:'numeric',month:'long',day:'numeric'});
  document.getElementById('sumTime').innerHTML='<span class="sum-empty">Not selected</span>';
  document.getElementById('sumType').innerHTML='<span class="sum-empty">—</span>';
  loadSlots(iso);
}

function loadSlots(date){
  const lbl=document.getElementById('slotsLabel');
  const con=document.getElementById('slotsContainer');
  lbl.style.fontStyle='normal';
  lbl.textContent='Available slots for '+new Date(date+'T00:00:00').toLocaleDateString('en-PH',{weekday:'short',month:'short',day:'numeric'})+':';
  con.innerHTML='<div class="slots-msg">Loading slots...</div>';
  fetch("{{ route('consultation.slots') }}?date="+date,{headers:{'X-Requested-With':'XMLHttpRequest'}})
  .then(r=>r.json())
  .then(data=>{
    if(!data.slots||data.slots.length===0){
      con.innerHTML='<div class="slots-msg">No slots available for this date. Please try another day.</div>';
      return;
    }
    con.innerHTML='<div class="slots-grid">'+
      data.slots.map(s=>`
        <button type="button" class="slot-btn${selectedSlot===s.id?' selected':''}"
          onclick="pickSlot(${s.id},'${s.time_range}','${s.type_label}',this)">
          ${s.time_range}
          <span class="slot-type">${s.type_label}</span>
        </button>`).join('')+
    '</div>';
  })
  .catch(()=>{ con.innerHTML='<div class="slots-msg">Could not load slots. Please try again.</div>'; });
}

function pickSlot(id,timeRange,typeLabel,btn){
  document.querySelectorAll('.slot-btn.selected').forEach(e=>e.classList.remove('selected'));
  btn.classList.add('selected');
  selectedSlot=id;
  document.getElementById('scheduleId').value=id;
  document.getElementById('sumTime').textContent=timeRange;
  document.getElementById('sumType').textContent=typeLabel;
}

function changeMonth(dir){
  curMonth+=dir;
  if(curMonth<0){curMonth=11;curYear--;} if(curMonth>11){curMonth=0;curYear++;}
  renderCalendar();
}

function toggleCollab(){
  document.getElementById('collabFields').classList.toggle('show',document.getElementById('collabCheck').checked);
}

renderCalendar();

// Restore old selection after validation failure
const oldScheduleId="{{ old('schedule_id') }}";
if(oldScheduleId) document.getElementById('scheduleId').value=oldScheduleId;
</script>
@endpush
