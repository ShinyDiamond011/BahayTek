@extends('layouts.app')
@section('title', $product->prod_name)

@push('styles')
<style>
.product-hero{background:linear-gradient(135deg,#1a2e12 0%,#2a4a1e 50%,#336a29 100%);padding:32px 0 24px;position:relative}
.product-hero::before{content:'';position:absolute;inset:0;background:radial-gradient(ellipse 60% 80% at 80% 40%,rgba(193,217,92,.06) 0%,transparent 60%)}
.product-hero-inner{max-width:1120px;margin:0 auto;padding:0 40px;position:relative;z-index:1}
.breadcrumb{display:flex;align-items:center;gap:8px;font-size:.78rem;color:rgba(255,255,255,.6)}
.breadcrumb a{color:rgba(255,255,255,.6);text-decoration:none}
.breadcrumb a:hover{color:var(--mint)}
.breadcrumb span{color:rgba(255,255,255,.85)}

.product-wrap{max-width:1120px;margin:0 auto;padding:32px 40px;display:grid;grid-template-columns:1fr 1fr;gap:40px;align-items:start}

.product-gallery{position:sticky;top:80px}
.product-img-main{width:100%;border-radius:16px;border:1px solid var(--border);background:var(--sand);aspect-ratio:1/1;object-fit:cover}
.product-img-placeholder{width:100%;border-radius:16px;border:1px solid var(--border);background:linear-gradient(135deg,var(--sand) 0%,#d4e8c0 100%);aspect-ratio:1/1;display:flex;align-items:center;justify-content:center}

.product-info{}
.product-info-cat{font-size:.68rem;font-weight:700;letter-spacing:2px;text-transform:uppercase;color:var(--emerald);margin-bottom:8px}
.product-info-name{font-family:'DM Serif Display',serif;font-size:1.9rem;color:var(--dark);line-height:1.2;margin-bottom:10px}
.product-info-price{font-size:1.6rem;font-weight:700;color:var(--dark);margin-bottom:6px}
.product-info-price small{font-size:.85rem;font-weight:400;color:var(--gray)}
.product-info-desc{font-size:.86rem;color:var(--gray);line-height:1.65;margin-bottom:20px}

.stock-badge{font-size:.63rem;font-weight:700;letter-spacing:.5px;text-transform:uppercase;padding:3px 10px;border-radius:20px;display:inline-block;margin-bottom:16px}
.stock-in{background:#dcfce7;color:#166534;border:1px solid #86efac}
.stock-low{background:#fef9c3;color:#854d0e;border:1px solid #fde68a}
.stock-out{background:#fee2e2;color:#991b1b;border:1px solid #fca5a5}

.variant-section{margin-bottom:20px}
.variant-label{font-size:.78rem;font-weight:700;color:var(--dark);margin-bottom:10px}
.variant-grid{display:flex;flex-direction:column;gap:8px}
.variant-option{display:flex;align-items:center;justify-content:space-between;padding:12px 16px;border:1.5px solid var(--border);border-radius:10px;cursor:pointer;transition:all .2s}
.variant-option:hover:not(.disabled){border-color:var(--emerald)}
.variant-option.selected{border-color:var(--forest);background:#f0f7ed}
.variant-option.disabled{opacity:.5;cursor:not-allowed}
.variant-option input{accent-color:var(--forest);margin-right:10px}
.variant-option-name{font-size:.83rem;font-weight:600;color:var(--dark)}
.variant-option-stock{font-size:.7rem;color:var(--gray)}
.variant-option-price{font-size:.88rem;font-weight:700;color:var(--forest)}

.qty-row{display:flex;align-items:center;gap:14px;margin-bottom:20px}
.qty-label{font-size:.78rem;font-weight:700;color:var(--dark)}
.qty-control{display:flex;align-items:center;gap:0;border:1.5px solid var(--border);border-radius:10px;overflow:hidden}
.qty-btn{width:36px;height:36px;border:none;background:#fff;font-size:1.1rem;font-weight:700;cursor:pointer;color:var(--dark);transition:background .15s;display:flex;align-items:center;justify-content:center}
.qty-btn:hover{background:var(--sand)}
.qty-val{width:44px;height:36px;border:none;border-left:1.5px solid var(--border);border-right:1.5px solid var(--border);text-align:center;font-size:.88rem;font-weight:600;font-family:inherit;color:var(--dark);background:#fff;outline:none}

.btn-cart{display:flex;align-items:center;justify-content:center;gap:8px;width:100%;padding:14px;border-radius:12px;background:var(--forest);color:#fff;border:none;font-size:.9rem;font-weight:700;font-family:inherit;cursor:pointer;transition:all .2s;margin-bottom:10px}
.btn-cart:hover{background:var(--green)}
.btn-cart:disabled{opacity:.4;cursor:not-allowed}

.toast{position:fixed;bottom:24px;right:24px;background:var(--dark);color:#fff;padding:12px 20px;border-radius:12px;font-size:.82rem;font-weight:500;z-index:999;transform:translateY(80px);opacity:0;transition:all .3s;display:flex;align-items:center;gap:8px}
.toast.show{transform:translateY(0);opacity:1}
.toast.success{background:#166534}
.toast.error{background:var(--red,#dc2626)}

@media(max-width:860px){
  .product-wrap{grid-template-columns:1fr;padding:20px 18px;gap:24px}
  .product-gallery{position:static}
  .product-hero-inner{padding:0 18px}
}
</style>
@endpush

@section('content')
<div class="product-hero">
  <div class="product-hero-inner">
    <div class="breadcrumb">
      <a href="{{ route('shop.index') }}">Shop</a>
      <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
      <a href="{{ route('shop.index') }}?category={{ urlencode($product->category) }}">{{ $product->category }}</a>
      <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
      <span>{{ $product->prod_name }}</span>
    </div>
  </div>
</div>

<div class="product-wrap">
  <!-- Gallery -->
  <div class="product-gallery">
    @if($product->image_url)
      <img class="product-img-main" src="{{ $product->image_url }}" alt="{{ $product->prod_name }}">
    @else
      <div class="product-img-placeholder">
        <svg width="80" height="80" viewBox="0 0 24 24" fill="none" stroke="var(--border)" stroke-width="1" stroke-linecap="round" stroke-linejoin="round"><path d="M21 16V8a2 2 0 00-1-1.73l-7-4a2 2 0 00-2 0l-7 4A2 2 0 003 8v8a2 2 0 001 1.73l7 4a2 2 0 002 0l7-4A2 2 0 0021 16z"/></svg>
      </div>
    @endif
  </div>

  <!-- Info -->
  <div class="product-info">
    <div class="product-info-cat">{{ $product->category }}</div>
    <h1 class="product-info-name">{{ $product->prod_name }}</h1>
    <div id="displayPrice" class="product-info-price">
      ₱{{ number_format($product->price, 2) }} <small>PHP</small>
    </div>
    <span class="stock-badge {{ $product->stock_level === 'in_stock' ? 'stock-in' : ($product->stock_level === 'low_stock' ? 'stock-low' : 'stock-out') }}">
      {{ str_replace('_', ' ', ucwords($product->stock_level, '_')) }}
    </span>
    @if($product->prod_description)
      <p class="product-info-desc">{{ $product->prod_description }}</p>
    @endif

    @if($product->variants->isNotEmpty())
    <div class="variant-section">
      <div class="variant-label">Select Variant</div>
      <div class="variant-grid" id="variantGrid">
        @foreach($product->variants as $variant)
        @php $effectivePrice = (float)$product->price + (float)$variant->price_modifier; @endphp
        <label class="variant-option {{ $variant->stock_qty < 1 ? 'disabled' : '' }}" data-price="{{ $effectivePrice }}" data-id="{{ $variant->id }}">
          <div style="display:flex;align-items:center;gap:8px;flex:1">
            <input type="radio" name="variantPick" value="{{ $variant->id }}" {{ $variant->stock_qty < 1 ? 'disabled' : '' }}
              onchange="selectVariant(this, {{ $effectivePrice }})">
            <div>
              <div class="variant-option-name">{{ $variant->var_name }}</div>
              <div class="variant-option-stock">
                @if($variant->stock_qty < 1) Out of stock
                @elseif($variant->stock_qty <= 5) Only {{ $variant->stock_qty }} left
                @else In stock
                @endif
              </div>
            </div>
          </div>
          <div class="variant-option-price">₱{{ number_format($effectivePrice, 2) }}</div>
        </label>
        @endforeach
      </div>
    </div>
    @endif

    <div class="qty-row">
      <div class="qty-label">Quantity</div>
      <div class="qty-control">
        <button class="qty-btn" onclick="changeQty(-1)">−</button>
        <input class="qty-val" type="number" id="qtyInput" value="1" min="1" max="99">
        <button class="qty-btn" onclick="changeQty(1)">+</button>
      </div>
    </div>

    <button class="btn-cart" id="addBtn" onclick="handleAddToCart()" {{ $product->stock_level === 'out_of_stock' ? 'disabled' : '' }}>
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 01-8 0"/></svg>
      Add to Cart
    </button>
    <a href="{{ route('shop.cart') }}" style="display:block;text-align:center;font-size:.82rem;color:var(--gray);text-decoration:none;font-weight:600">
      View Cart →
    </a>
  </div>
</div>

<div class="toast" id="toast"></div>

@php $variants_data = $product->variants->map(fn($v) => [
  'id' => $v->id,
  'modifier' => (float)$v->price_modifier,
  'stock' => $v->stock_qty,
])->keyBy('id')->all(); @endphp
@endsection

@push('scripts')
<script>
const BASE_PRICE = {{ (float)$product->price }};
const VARIANTS = @json($variants_data);
const CSRF = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
let selectedVariantId = null;

@if($product->variants->isEmpty())
selectedVariantId = {{ $product->variants->first()?->id ?? 'null' }};
@endif

function selectVariant(radio, price){
  selectedVariantId = parseInt(radio.value);
  document.querySelectorAll('.variant-option').forEach(el => el.classList.remove('selected'));
  radio.closest('.variant-option').classList.add('selected');
  document.getElementById('displayPrice').innerHTML = '₱'+price.toLocaleString('en-PH',{minimumFractionDigits:2})+' <small>PHP</small>';
}

function changeQty(delta){
  const inp = document.getElementById('qtyInput');
  let v = parseInt(inp.value)||1;
  v = Math.min(Math.max(1, v+delta), 99);
  inp.value = v;
}

function showToast(msg, type='success'){
  const t = document.getElementById('toast');
  t.textContent = msg; t.className = 'toast '+type;
  setTimeout(()=>t.classList.add('show'),10);
  setTimeout(()=>t.classList.remove('show'),3000);
}

function handleAddToCart(){
  @if($product->variants->isNotEmpty())
  if(!selectedVariantId){ showToast('Please select a variant','error'); return; }
  @else
  if(!selectedVariantId){ showToast('No variant available','error'); return; }
  @endif

  const qty = parseInt(document.getElementById('qtyInput').value)||1;
  const btn = document.getElementById('addBtn');
  btn.disabled = true;

  fetch("{{ route('shop.cart.add') }}", {
    method:'POST',
    headers:{'Content-Type':'application/json','X-CSRF-TOKEN':CSRF,'X-Requested-With':'XMLHttpRequest'},
    body: JSON.stringify({variant_id:selectedVariantId, quantity:qty})
  })
  .then(r=>r.json())
  .then(d=>{
    if(d.ok){
      showToast(d.message,'success');
    } else {
      showToast(d.message||'Error adding to cart','error');
    }
  })
  .catch(()=>showToast('Network error','error'))
  .finally(()=>{ btn.disabled=false; });
}

// Auto-select first available variant
(function(){
  const first = document.querySelector('.variant-option:not(.disabled) input[type=radio]');
  if(first){ first.checked=true; first.dispatchEvent(new Event('change')); }
})();
</script>
@endpush
