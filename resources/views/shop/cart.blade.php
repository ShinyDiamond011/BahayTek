@extends('layouts.app')
@section('title','Cart')

@push('styles')
<style>
.page-hero{background:linear-gradient(135deg,#1a2e12 0%,#2a4a1e 50%,#336a29 100%);padding:40px 0 28px;position:relative}
.page-hero::before{content:'';position:absolute;inset:0;background:radial-gradient(ellipse 60% 80% at 80% 40%,rgba(193,217,92,.06) 0%,transparent 60%)}
.page-hero-inner{max-width:1120px;margin:0 auto;padding:0 40px;position:relative;z-index:1}
.page-hero h1{font-family:'DM Serif Display',serif;font-size:1.8rem;color:#fff}
.page-hero p{font-size:.82rem;color:rgba(255,255,255,.55);margin-top:4px}

.cart-wrap{max-width:1120px;margin:0 auto;padding:32px 40px;display:grid;grid-template-columns:1fr 320px;gap:24px;align-items:start}
.cart-table-card{background:#fff;border:1px solid var(--border);border-radius:16px;box-shadow:var(--sh-sm);overflow:hidden}
.cart-table-header{padding:16px 20px;border-bottom:1px solid var(--border);font-size:.78rem;font-weight:700;color:var(--gray);letter-spacing:.5px}
.cart-item-row{display:grid;grid-template-columns:auto 1fr auto auto auto;gap:14px;align-items:center;padding:16px 20px;border-bottom:1px solid var(--border)}
.cart-item-row:last-child{border-bottom:none}
.ci-img{width:62px;height:62px;border-radius:10px;object-fit:cover;background:var(--sand);flex-shrink:0}
.ci-img-ph{width:62px;height:62px;border-radius:10px;background:var(--sand);display:flex;align-items:center;justify-content:center;flex-shrink:0}
.ci-name{font-size:.83rem;font-weight:700;color:var(--dark);margin-bottom:2px}
.ci-variant{font-size:.72rem;color:var(--gray)}
.ci-price{font-size:.78rem;font-weight:600;color:var(--forest);margin-top:3px}
.ci-qty{display:flex;align-items:center;border:1.5px solid var(--border);border-radius:9px;overflow:hidden}
.ci-qty-btn{width:30px;height:30px;border:none;background:#fff;font-size:1rem;font-weight:700;cursor:pointer;color:var(--dark);transition:background .15s;display:flex;align-items:center;justify-content:center}
.ci-qty-btn:hover{background:var(--sand)}
.ci-qty-val{width:38px;height:30px;border:none;border-left:1.5px solid var(--border);border-right:1.5px solid var(--border);text-align:center;font-size:.82rem;font-weight:600;font-family:inherit;color:var(--dark);background:#fff;outline:none}
.ci-sub{font-size:.88rem;font-weight:700;color:var(--dark);white-space:nowrap;min-width:80px;text-align:right}
.ci-remove{background:none;border:none;cursor:pointer;color:var(--gray);padding:4px;display:flex;transition:color .2s;flex-shrink:0}
.ci-remove:hover{color:#dc2626}

.cart-summary{background:#fff;border:1px solid var(--border);border-radius:16px;box-shadow:var(--sh-sm);padding:22px;position:sticky;top:80px}
.cs-title{font-size:.88rem;font-weight:700;color:var(--dark);margin-bottom:16px;padding-bottom:12px;border-bottom:1px solid var(--border)}
.cs-row{display:flex;justify-content:space-between;font-size:.83rem;color:var(--gray);margin-bottom:8px}
.cs-row.total{margin-top:12px;padding-top:12px;border-top:1px solid var(--border);font-size:1rem;font-weight:700;color:var(--dark)}
.btn-checkout{display:block;width:100%;padding:13px;border-radius:12px;background:var(--forest);color:#fff;text-align:center;text-decoration:none;font-size:.88rem;font-weight:700;margin-top:16px;transition:all .2s}
.btn-checkout:hover{background:var(--green)}
.btn-shop{display:block;text-align:center;font-size:.78rem;color:var(--gray);text-decoration:none;margin-top:10px;font-weight:600}
.btn-shop:hover{color:var(--forest)}

.empty-state{text-align:center;padding:64px 24px;grid-column:1/-1}
.empty-icon{width:72px;height:72px;border-radius:50%;background:var(--sand);display:flex;align-items:center;justify-content:center;margin:0 auto 16px}

@media(max-width:820px){
  .cart-wrap{grid-template-columns:1fr;padding:20px 18px}
  .cart-summary{position:static}
  .cart-item-row{grid-template-columns:auto 1fr auto auto;gap:10px}
  .ci-sub{display:none}
}
</style>
@endpush

@section('content')
<div class="page-hero">
  <div class="page-hero-inner">
    <h1>Your Cart</h1>
    <p>{{ count($items) }} item{{ count($items) !== 1 ? 's' : '' }} ready for checkout</p>
  </div>
</div>

<div class="cart-wrap">
  @if(empty($items))
  <div class="empty-state">
    <div class="empty-icon">
      <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="var(--emerald)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 01-8 0"/></svg>
    </div>
    <div style="font-size:1.1rem;font-weight:700;color:var(--dark);margin-bottom:8px">Your cart is empty</div>
    <div style="font-size:.85rem;color:var(--gray);margin-bottom:22px">Browse our products and add items to your cart.</div>
    <a href="{{ route('shop.index') }}" style="display:inline-flex;align-items:center;gap:6px;padding:10px 22px;border-radius:10px;background:var(--forest);color:#fff;text-decoration:none;font-size:.84rem;font-weight:700">
      Browse Products
    </a>
  </div>
  @else
  <!-- Items -->
  <div>
    <div class="cart-table-card">
      <div class="cart-table-header">CART ITEMS</div>
      @foreach($items as $item)
      <div class="cart-item-row" id="row-{{ $item['variant_id'] }}">
        @if($item['product']->image_url)
          <img class="ci-img" src="{{ $item['product']->image_url }}" alt="{{ $item['product']->prod_name }}">
        @else
          <div class="ci-img-ph">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="var(--border)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21 16V8a2 2 0 00-1-1.73l-7-4a2 2 0 00-2 0l-7 4A2 2 0 003 8v8a2 2 0 001 1.73l7 4a2 2 0 002 0l7-4A2 2 0 0021 16z"/></svg>
          </div>
        @endif
        <div>
          <div class="ci-name">{{ $item['product']->prod_name }}</div>
          <div class="ci-variant">{{ $item['variant']->var_name }}</div>
          <div class="ci-price">₱{{ number_format($item['unit_price'], 2) }} each</div>
        </div>
        <div class="ci-qty" data-id="{{ $item['variant_id'] }}">
          <button class="ci-qty-btn" data-action="dec" data-id="{{ $item['variant_id'] }}">−</button>
          <input class="ci-qty-val" type="number" value="{{ $item['quantity'] }}" min="0" max="99"
            id="qty-{{ $item['variant_id'] }}" data-id="{{ $item['variant_id'] }}" data-action="set">
          <button class="ci-qty-btn" data-action="inc" data-id="{{ $item['variant_id'] }}">+</button>
        </div>
        <div class="ci-sub" id="sub-{{ $item['variant_id'] }}">₱{{ number_format($item['subtotal'], 2) }}</div>
        <button class="ci-remove" data-id="{{ $item['variant_id'] }}" title="Remove">
          <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 01-2 2H8a2 2 0 01-2-2L5 6"/><path d="M10 11v6M14 11v6"/><path d="M9 6V4a1 1 0 011-1h4a1 1 0 011 1v2"/></svg>
        </button>
      </div>
      @endforeach
    </div>
    <div style="margin-top:14px">
      <a href="{{ route('shop.index') }}" style="font-size:.8rem;color:var(--gray);text-decoration:none;font-weight:600">← Continue Shopping</a>
    </div>
  </div>

  <!-- Summary -->
  <div class="cart-summary">
    <div class="cs-title">Order Summary</div>
    <div class="cs-row">
      <span>Subtotal ({{ $cartCount }} item{{ $cartCount !== 1 ? 's' : '' }})</span>
      <span id="summaryTotal">₱{{ number_format($total, 2) }}</span>
    </div>
    <div class="cs-row">
      <span>Shipping</span>
      <span style="color:var(--emerald);font-weight:600">At checkout</span>
    </div>
    <div class="cs-row total">
      <span>Estimated Total</span>
      <span id="summaryTotalFinal">₱{{ number_format($total, 2) }}</span>
    </div>
    @auth
      <a href="{{ route('shop.checkout') }}" class="btn-checkout">Proceed to Checkout</a>
    @else
      <a href="{{ route('login') }}" class="btn-checkout">Login to Checkout</a>
    @endauth
    <a href="{{ route('shop.index') }}" class="btn-shop">Continue Shopping</a>
  </div>
  @endif
</div>

@endsection

@push('scripts')
<script>
const CSRF = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

document.addEventListener('click', function(e){
  const btn = e.target.closest('[data-action]');
  if(!btn) return;
  const id = btn.dataset.id;
  const action = btn.dataset.action;
  if(action === 'dec' || action === 'inc'){
    const inp = document.getElementById('qty-'+id);
    const newVal = Math.max(0, (parseInt(inp.value)||1) + (action==='inc' ? 1 : -1));
    inp.value = newVal;
    setQty(id, newVal);
  }
  if(btn.classList.contains('ci-remove')){
    removeItem(id);
  }
});

document.addEventListener('change', function(e){
  if(e.target.dataset.action === 'set'){
    setQty(e.target.dataset.id, e.target.value);
  }
});

function setQty(variantId, qty){
  qty = parseInt(qty);
  if(isNaN(qty)||qty<0) qty = 0;

  fetch('/cart/'+variantId, {
    method:'PATCH',
    headers:{'Content-Type':'application/json','X-CSRF-TOKEN':CSRF,'X-Requested-With':'XMLHttpRequest'},
    body: JSON.stringify({quantity:qty})
  })
  .then(r=>r.json())
  .then(d=>{
    if(d.ok){
      if(qty===0){
        document.getElementById('row-'+variantId)?.remove();
        if(d.cartCount===0) location.reload();
      }
      const fmt = '₱'+parseFloat(d.total).toLocaleString('en-PH',{minimumFractionDigits:2});
      const sub = document.getElementById('sub-'+variantId);
      if(sub && qty>0){
        const priceEl = document.querySelector('#row-'+variantId+' .ci-price');
        if(priceEl){
          const unitPrice = parseFloat(priceEl.textContent.replace(/[₱,\s]/g,'').replace('each',''));
          sub.textContent = '₱'+(unitPrice*qty).toLocaleString('en-PH',{minimumFractionDigits:2});
        }
      }
      document.getElementById('summaryTotal').textContent = fmt;
      document.getElementById('summaryTotalFinal').textContent = fmt;
    }
  });
}

function removeItem(variantId){
  fetch('/cart/'+variantId, {
    method:'DELETE',
    headers:{'X-CSRF-TOKEN':CSRF,'X-Requested-With':'XMLHttpRequest'}
  })
  .then(r=>r.json())
  .then(d=>{
    if(d.ok){
      document.getElementById('row-'+variantId)?.remove();
      if(d.cartCount===0) location.reload();
    }
  });
}
</script>
@endpush
