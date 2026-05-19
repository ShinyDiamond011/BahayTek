@extends('layouts.app')
@section('title','Checkout')

@push('styles')
<style>
.page-hero{background:linear-gradient(135deg,#1a2e12 0%,#2a4a1e 50%,#336a29 100%);padding:40px 0 28px;position:relative}
.page-hero::before{content:'';position:absolute;inset:0;background:radial-gradient(ellipse 60% 80% at 80% 40%,rgba(193,217,92,.06) 0%,transparent 60%)}
.page-hero-inner{max-width:1120px;margin:0 auto;padding:0 40px;position:relative;z-index:1}
.page-hero h1{font-family:'DM Serif Display',serif;font-size:1.8rem;color:#fff}
.page-hero p{font-size:.82rem;color:rgba(255,255,255,.55);margin-top:4px}

.checkout-wrap{max-width:1120px;margin:0 auto;padding:32px 40px;display:grid;grid-template-columns:1fr 340px;gap:24px;align-items:start}

.form-card{background:#fff;border:1px solid var(--border);border-radius:16px;box-shadow:var(--sh-sm);overflow:hidden;margin-bottom:18px}
.form-card-header{padding:16px 22px;border-bottom:1px solid var(--border);font-size:.88rem;font-weight:700;color:var(--dark);display:flex;align-items:center;gap:8px}
.form-card-body{padding:22px}
.form-grid{display:grid;grid-template-columns:1fr 1fr;gap:14px}
.form-full{grid-column:1/-1}
.form-group{display:flex;flex-direction:column;gap:5px}
.form-label{font-size:.75rem;font-weight:700;color:var(--dark);letter-spacing:.3px}
.form-control{padding:9px 12px;border-radius:9px;border:1.5px solid var(--border);font-size:.83rem;font-family:inherit;color:var(--dark);background:#fff;outline:none;transition:border-color .2s}
.form-control:focus{border-color:var(--forest);box-shadow:0 0 0 3px rgba(51,106,41,.08)}
.form-control.error{border-color:#dc2626}
.form-error{font-size:.72rem;color:#dc2626;margin-top:2px}
textarea.form-control{resize:vertical;min-height:70px}

.payment-option{display:flex;align-items:center;gap:12px;padding:14px 16px;border:1.5px solid var(--border);border-radius:10px;cursor:pointer;transition:all .2s;margin-bottom:8px}
.payment-option:hover{border-color:var(--emerald)}
.payment-option.selected{border-color:var(--forest);background:#f0f7ed}
.payment-option input{accent-color:var(--forest)}
.payment-icon{width:36px;height:36px;border-radius:8px;display:flex;align-items:center;justify-content:center;flex-shrink:0}
.payment-icon.cod{background:#fef9c3}
.payment-icon.gcash{background:#dbeafe}
.payment-icon.pm{background:#ede9fe}
.payment-name{font-size:.83rem;font-weight:700;color:var(--dark)}
.payment-desc{font-size:.72rem;color:var(--gray)}

.order-summary{background:#fff;border:1px solid var(--border);border-radius:16px;box-shadow:var(--sh-sm);padding:22px;position:sticky;top:80px}
.os-title{font-size:.88rem;font-weight:700;color:var(--dark);margin-bottom:16px;padding-bottom:12px;border-bottom:1px solid var(--border)}
.os-item{display:flex;gap:10px;padding:10px 0;border-bottom:1px solid var(--border)}
.os-item:last-of-type{border-bottom:none}
.os-item-img{width:44px;height:44px;border-radius:8px;object-fit:cover;background:var(--sand);flex-shrink:0}
.os-item-ph{width:44px;height:44px;border-radius:8px;background:var(--sand);display:flex;align-items:center;justify-content:center;flex-shrink:0}
.os-item-name{font-size:.78rem;font-weight:700;color:var(--dark);margin-bottom:1px}
.os-item-variant{font-size:.68rem;color:var(--gray)}
.os-item-price{font-size:.78rem;font-weight:600;color:var(--forest)}
.os-row{display:flex;justify-content:space-between;font-size:.82rem;color:var(--gray);margin-bottom:8px;margin-top:12px}
.os-total{display:flex;justify-content:space-between;padding-top:12px;border-top:1px solid var(--border);font-size:1rem;font-weight:700;color:var(--dark)}
.btn-place{width:100%;padding:14px;border-radius:12px;background:var(--forest);color:#fff;border:none;font-size:.9rem;font-weight:700;font-family:inherit;cursor:pointer;margin-top:16px;transition:all .2s;display:flex;align-items:center;justify-content:center;gap:8px}
.btn-place:hover{background:var(--green)}
.btn-place:disabled{opacity:.5;cursor:not-allowed}
</style>
@endpush

@section('content')
<div class="page-hero">
  <div class="page-hero-inner">
    <h1>Checkout</h1>
    <p>Complete your order — we ship across Bicol Region and beyond.</p>
  </div>
</div>

<form method="POST" action="{{ route('shop.order.place') }}" id="checkoutForm">
@csrf
<div class="checkout-wrap">
  <!-- Left column -->
  <div>
    <!-- Contact Info -->
    <div class="form-card">
      <div class="form-card-header">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="var(--forest)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
        Contact Information
      </div>
      <div class="form-card-body">
        <div class="form-grid">
          <div class="form-group">
            <label class="form-label">First Name *</label>
            <input class="form-control @error('first_name') error @enderror" type="text" name="first_name"
              value="{{ old('first_name', $user?->first_name) }}" required>
            @error('first_name')<div class="form-error">{{ $message }}</div>@enderror
          </div>
          <div class="form-group">
            <label class="form-label">Last Name *</label>
            <input class="form-control @error('last_name') error @enderror" type="text" name="last_name"
              value="{{ old('last_name', $user?->last_name) }}" required>
            @error('last_name')<div class="form-error">{{ $message }}</div>@enderror
          </div>
          <div class="form-group">
            <label class="form-label">Email Address *</label>
            <input class="form-control @error('email') error @enderror" type="email" name="email"
              value="{{ old('email', $user?->email) }}" required>
            @error('email')<div class="form-error">{{ $message }}</div>@enderror
          </div>
          <div class="form-group">
            <label class="form-label">Phone Number *</label>
            <input class="form-control @error('phone') error @enderror" type="tel" name="phone"
              value="{{ old('phone', $user?->phone) }}" placeholder="09xx xxx xxxx" required>
            @error('phone')<div class="form-error">{{ $message }}</div>@enderror
          </div>
        </div>
      </div>
    </div>

    <!-- Shipping Address -->
    <div class="form-card">
      <div class="form-card-header">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="var(--forest)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg>
        Shipping Address
      </div>
      <div class="form-card-body">
        <div class="form-grid">
          <div class="form-group form-full">
            <label class="form-label">Street Address *</label>
            <input class="form-control @error('street_address') error @enderror" type="text" name="street_address"
              value="{{ old('street_address') }}" placeholder="House No., Street, Barangay" required>
            @error('street_address')<div class="form-error">{{ $message }}</div>@enderror
          </div>
          <div class="form-group">
            <label class="form-label">City / Municipality *</label>
            <input class="form-control @error('city') error @enderror" type="text" name="city"
              value="{{ old('city') }}" placeholder="e.g., Legazpi City" required>
            @error('city')<div class="form-error">{{ $message }}</div>@enderror
          </div>
          <div class="form-group">
            <label class="form-label">Province *</label>
            <input class="form-control @error('province') error @enderror" type="text" name="province"
              value="{{ old('province', 'Albay') }}" required>
            @error('province')<div class="form-error">{{ $message }}</div>@enderror
          </div>
        </div>
      </div>
    </div>

    <!-- Payment -->
    <div class="form-card">
      <div class="form-card-header">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="var(--forest)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
        Payment Method
      </div>
      <div class="form-card-body">
        @error('payment_method')<div class="form-error" style="margin-bottom:10px">{{ $message }}</div>@enderror

        <label class="payment-option {{ old('payment_method','cod')==='cod' ? 'selected' : '' }}" onclick="selectPayment(this)">
          <input type="radio" name="payment_method" value="cod" {{ old('payment_method','cod')==='cod' ? 'checked' : '' }}>
          <div class="payment-icon cod">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#854d0e" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="6" width="20" height="12" rx="2"/><circle cx="12" cy="12" r="2"/><path d="M6 12h.01M18 12h.01"/></svg>
          </div>
          <div>
            <div class="payment-name">Cash on Delivery</div>
            <div class="payment-desc">Pay when your order arrives</div>
          </div>
        </label>

        <label class="payment-option {{ old('payment_method')==='gcash' ? 'selected' : '' }}" onclick="selectPayment(this)">
          <input type="radio" name="payment_method" value="gcash" {{ old('payment_method')==='gcash' ? 'checked' : '' }}>
          <div class="payment-icon gcash">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#1d4ed8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
          </div>
          <div>
            <div class="payment-name">GCash</div>
            <div class="payment-desc">Pay via GCash mobile wallet</div>
          </div>
        </label>

        <label class="payment-option {{ old('payment_method')==='paymongo' ? 'selected' : '' }}" onclick="selectPayment(this)">
          <input type="radio" name="payment_method" value="paymongo" {{ old('payment_method')==='paymongo' ? 'checked' : '' }}>
          <div class="payment-icon pm">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#6d28d9" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
          </div>
          <div>
            <div class="payment-name">Credit / Debit Card</div>
            <div class="payment-desc">Powered by PayMongo — Visa, Mastercard</div>
          </div>
        </label>
      </div>
    </div>

    <!-- Notes -->
    <div class="form-card">
      <div class="form-card-header">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="var(--forest)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
        Order Notes <span style="font-weight:400;color:var(--gray)">(optional)</span>
      </div>
      <div class="form-card-body">
        <textarea class="form-control" name="notes" rows="3"
          placeholder="Special instructions, delivery notes...">{{ old('notes') }}</textarea>
      </div>
    </div>
  </div>

  <!-- Order Summary -->
  <div>
    <div class="order-summary">
      <div class="os-title">Order Summary ({{ count($items) }} items)</div>
      @foreach($items as $item)
      <div class="os-item">
        @if($item['product']->image_url)
          <img class="os-item-img" src="{{ $item['product']->image_url }}" alt="{{ $item['product']->prod_name }}">
        @else
          <div class="os-item-ph">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="var(--border)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21 16V8a2 2 0 00-1-1.73l-7-4a2 2 0 00-2 0l-7 4A2 2 0 003 8v8a2 2 0 001 1.73l7 4a2 2 0 002 0l7-4A2 2 0 0021 16z"/></svg>
          </div>
        @endif
        <div style="flex:1;min-width:0">
          <div class="os-item-name">{{ $item['product']->prod_name }}</div>
          <div class="os-item-variant">{{ $item['variant']->var_name }} × {{ $item['quantity'] }}</div>
          <div class="os-item-price">₱{{ number_format($item['subtotal'], 2) }}</div>
        </div>
      </div>
      @endforeach
      <div class="os-row">
        <span>Subtotal</span><span>₱{{ number_format($total, 2) }}</span>
      </div>
      <div class="os-row">
        <span>Shipping</span><span style="color:var(--emerald);font-weight:600">TBD</span>
      </div>
      <div class="os-total">
        <span>Total</span><span>₱{{ number_format($total, 2) }}</span>
      </div>
      <button type="submit" class="btn-place" id="placeBtn">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
        Place Order
      </button>
      <a href="{{ route('shop.cart') }}" style="display:block;text-align:center;font-size:.78rem;color:var(--gray);text-decoration:none;margin-top:10px;font-weight:600">← Back to Cart</a>
    </div>
  </div>
</div>
</form>
@endsection

@push('scripts')
<script>
function selectPayment(label){
  document.querySelectorAll('.payment-option').forEach(el=>el.classList.remove('selected'));
  label.classList.add('selected');
}

document.getElementById('checkoutForm').addEventListener('submit', function(){
  const btn = document.getElementById('placeBtn');
  btn.disabled = true;
  btn.innerHTML = '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="animation:spin 1s linear infinite"><polyline points="23 4 23 10 17 10"/><path d="M20.49 15a9 9 0 11-2.12-9.36L23 10"/></svg> Placing Order...';
});
</script>
<style>@keyframes spin{to{transform:rotate(360deg)}}</style>
@endpush
