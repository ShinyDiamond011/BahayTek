@extends('layouts.app')
@section('title','Shop')

@push('styles')
<style>
.shop-hero{background:linear-gradient(135deg,#1a2e12 0%,#2a4a1e 50%,#336a29 100%);padding:72px 0 36px;position:relative;overflow:hidden}
.shop-hero::before{content:'';position:absolute;inset:0;background:radial-gradient(ellipse 60% 80% at 80% 40%,rgba(193,217,92,.06) 0%,transparent 60%)}
.shop-hero-inner{max-width:1120px;margin:0 auto;padding:0 40px;position:relative;z-index:1;display:flex;align-items:center;justify-content:space-between;gap:20px;flex-wrap:wrap}
.shop-hero h1{font-family:'DM Serif Display',serif;font-size:2rem;color:#fff;line-height:1.2}
.shop-hero h1 em{font-style:italic;color:var(--mint)}
.shop-hero p{color:rgba(255,255,255,.6);font-size:.85rem;margin-top:6px}
.cart-fab{display:flex;align-items:center;gap:8px;padding:10px 20px;border-radius:12px;background:var(--mint);color:var(--dark);font-size:.82rem;font-weight:700;text-decoration:none;transition:all .2s;border:none;cursor:pointer;font-family:inherit;position:relative}
.cart-fab:hover{background:#eaf59d}
.cart-badge{position:absolute;top:-6px;right:-6px;width:18px;height:18px;border-radius:50%;background:var(--red);color:#fff;font-size:.6rem;font-weight:700;display:flex;align-items:center;justify-content:center}

/* LAYOUT */
.shop-wrap{max-width:1120px;margin:0 auto;padding:28px 40px;display:grid;grid-template-columns:220px 1fr;gap:24px;align-items:start}

/* SIDEBAR FILTERS */
.filter-card{background:#fff;border:1px solid var(--border);border-radius:14px;box-shadow:var(--sh-sm);overflow:hidden;position:sticky;top:80px}
.filter-header{padding:14px 18px;border-bottom:1px solid var(--border);font-size:.82rem;font-weight:700;color:var(--dark);display:flex;align-items:center;justify-content:space-between}
.filter-body{padding:16px 18px}
.filter-section{margin-bottom:18px}
.filter-section:last-child{margin-bottom:0}
.filter-label{font-size:.68rem;font-weight:700;letter-spacing:1.5px;text-transform:uppercase;color:var(--gray);margin-bottom:8px}
.filter-check{display:flex;align-items:center;gap:8px;padding:4px 0;cursor:pointer;font-size:.8rem;color:var(--dark)}
.filter-check input{accent-color:var(--forest);width:14px;height:14px}
.filter-input{width:100%;padding:7px 10px;border-radius:8px;border:1.5px solid var(--border);font-size:.78rem;font-family:inherit;outline:none;color:var(--dark);background:#fafcf8}
.filter-input:focus{border-color:var(--forest)}
.btn-filter{width:100%;padding:9px;border-radius:9px;background:var(--forest);color:#fff;border:none;font-size:.8rem;font-weight:700;font-family:inherit;cursor:pointer;transition:all .2s;margin-top:14px}
.btn-filter:hover{background:var(--green)}
.btn-clear{display:block;text-align:center;font-size:.73rem;color:var(--gray);text-decoration:none;margin-top:8px}
.btn-clear:hover{color:var(--forest)}

/* MAIN CONTENT */
.shop-toolbar{display:flex;align-items:center;justify-content:space-between;gap:12px;margin-bottom:18px;flex-wrap:wrap}
.search-wrap{flex:1;position:relative;min-width:180px}
.search-wrap svg{position:absolute;left:12px;top:50%;transform:translateY(-50%);color:var(--gray);pointer-events:none}
.search-input{width:100%;padding:9px 12px 9px 36px;border-radius:10px;border:1.5px solid var(--border);font-size:.82rem;font-family:inherit;outline:none;color:var(--dark);background:#fff;transition:border-color .2s}
.search-input:focus{border-color:var(--forest);box-shadow:0 0 0 3px rgba(51,106,41,.08)}
.sort-select{padding:9px 12px;border-radius:10px;border:1.5px solid var(--border);font-size:.8rem;font-family:inherit;outline:none;color:var(--dark);background:#fff;cursor:pointer;transition:border-color .2s}
.sort-select:focus{border-color:var(--forest)}

/* PRODUCT GRID */
.product-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:16px}
.product-card{background:#fff;border:1px solid var(--border);border-radius:14px;box-shadow:var(--sh-sm);overflow:hidden;transition:all .2s;display:flex;flex-direction:column}
.product-card:hover{box-shadow:var(--sh-md);transform:translateY(-2px)}
.product-img{width:100%;height:170px;object-fit:cover;background:var(--sand)}
.product-img-placeholder{width:100%;height:170px;background:linear-gradient(135deg,var(--sand) 0%,#d4e8c0 100%);display:flex;align-items:center;justify-content:center}
.product-body{padding:14px;flex:1;display:flex;flex-direction:column}
.product-cat{font-size:.62rem;font-weight:700;letter-spacing:1.5px;text-transform:uppercase;color:var(--emerald);margin-bottom:6px}
.product-name{font-size:.87rem;font-weight:700;color:var(--dark);margin-bottom:4px;line-height:1.35}
.product-desc{font-size:.75rem;color:var(--gray);line-height:1.5;flex:1;margin-bottom:10px;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden}
.product-footer{display:flex;align-items:center;justify-content:space-between;gap:8px;margin-top:auto}
.product-price{font-size:1rem;font-weight:700;color:var(--dark)}
.product-price span{font-size:.7rem;font-weight:500;color:var(--gray)}
.stock-badge{font-size:.63rem;font-weight:700;letter-spacing:.5px;text-transform:uppercase;padding:2px 8px;border-radius:20px}
.stock-in{background:#dcfce7;color:#166534;border:1px solid #86efac}
.stock-low{background:#fef9c3;color:#854d0e;border:1px solid #fde68a}
.stock-out{background:var(--red-lt);color:var(--red);border:1px solid #fca5a5}
.btn-add{padding:8px 14px;border-radius:9px;background:var(--forest);color:#fff;border:none;font-size:.76rem;font-weight:700;font-family:inherit;cursor:pointer;transition:all .2s;display:flex;align-items:center;gap:5px;white-space:nowrap}
.btn-add:hover{background:var(--green)}
.btn-add:disabled{opacity:.4;cursor:not-allowed}
.btn-detail{padding:7px 12px;border-radius:9px;background:transparent;color:var(--gray);border:1.5px solid var(--border);font-size:.75rem;font-weight:600;font-family:inherit;cursor:pointer;transition:all .2s;text-decoration:none;display:inline-flex;align-items:center}
.btn-detail:hover{border-color:var(--forest);color:var(--forest)}

/* EMPTY */
.empty-state{text-align:center;padding:60px 24px;grid-column:1/-1}
.empty-icon{width:64px;height:64px;border-radius:50%;background:var(--sand);display:flex;align-items:center;justify-content:center;margin:0 auto 14px}

/* TOAST */
.toast{position:fixed;bottom:24px;right:24px;background:var(--dark);color:#fff;padding:12px 20px;border-radius:12px;font-size:.82rem;font-weight:500;z-index:999;transform:translateY(80px);opacity:0;transition:all .3s;display:flex;align-items:center;gap:8px}
.toast.show{transform:translateY(0);opacity:1}
.toast.success{background:#166534}
.toast.error{background:var(--red)}

/* CART DRAWER */
.cart-overlay{position:fixed;inset:0;background:rgba(0,0,0,.4);z-index:300;opacity:0;pointer-events:none;transition:opacity .3s}
.cart-overlay.open{opacity:1;pointer-events:all}
.cart-drawer{position:fixed;top:0;right:0;height:100vh;width:380px;background:#fff;z-index:301;transform:translateX(100%);transition:transform .3s;display:flex;flex-direction:column;box-shadow:-8px 0 32px rgba(0,0,0,.12)}
.cart-drawer.open{transform:translateX(0)}
.cart-drawer-header{padding:18px 22px;border-bottom:1px solid var(--border);display:flex;align-items:center;justify-content:space-between}
.cart-drawer-title{font-size:.95rem;font-weight:700;color:var(--dark)}
.cart-close{background:none;border:none;cursor:pointer;color:var(--gray);padding:4px;display:flex}
.cart-drawer-body{flex:1;overflow-y:auto;padding:16px 22px}
.cart-empty{text-align:center;padding:40px 0;color:var(--gray);font-size:.83rem}
.cart-item{display:flex;gap:12px;padding:12px 0;border-bottom:1px solid var(--border)}
.cart-item:last-child{border-bottom:none}
.cart-item-img{width:52px;height:52px;border-radius:8px;object-fit:cover;background:var(--sand);flex-shrink:0}
.cart-item-info{flex:1;min-width:0}
.cart-item-name{font-size:.8rem;font-weight:700;color:var(--dark);white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
.cart-item-variant{font-size:.72rem;color:var(--gray)}
.cart-item-price{font-size:.78rem;font-weight:600;color:var(--forest);margin-top:3px}
.cart-item-actions{display:flex;align-items:center;gap:6px;margin-top:6px}
.qty-btn{width:24px;height:24px;border-radius:6px;border:1.5px solid var(--border);background:#fff;font-size:.9rem;font-weight:700;cursor:pointer;display:flex;align-items:center;justify-content:center;transition:all .2s;color:var(--dark)}
.qty-btn:hover{border-color:var(--forest);color:var(--forest)}
.qty-display{font-size:.8rem;font-weight:600;min-width:20px;text-align:center}
.cart-item-remove{background:none;border:none;cursor:pointer;color:var(--gray);font-size:.72rem;margin-left:auto;transition:color .2s}
.cart-item-remove:hover{color:var(--red)}
.cart-drawer-footer{padding:16px 22px;border-top:1px solid var(--border)}
.cart-total{display:flex;justify-content:space-between;align-items:center;margin-bottom:14px}
.cart-total-label{font-size:.82rem;font-weight:600;color:var(--gray)}
.cart-total-val{font-size:1.1rem;font-weight:700;color:var(--dark)}
.btn-checkout{display:block;width:100%;padding:13px;border-radius:12px;background:var(--forest);color:#fff;text-align:center;text-decoration:none;font-size:.88rem;font-weight:700;transition:all .2s}
.btn-checkout:hover{background:var(--green)}

@media(max-width:900px){
  .shop-wrap{grid-template-columns:1fr;padding:18px 18px}
  .filter-card{position:static}
  .product-grid{grid-template-columns:repeat(2,1fr)}
  .cart-drawer{width:320px}
}
@media(max-width:540px){
  .product-grid{grid-template-columns:1fr}
  .shop-hero-inner{padding:0 18px}
}
</style>
@endpush

@section('content')
<!-- HERO -->
<div class="shop-hero">
  <div class="shop-hero-inner">
    <div>
      <h1>BAHAYTEK <em>Shop</em></h1>
      <p>Sustainable technology products — solar, biogas, agriculture, and more.</p>
    </div>
    <button class="cart-fab" onclick="openCart()">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 01-8 0"/></svg>
      Cart
      <span class="cart-badge" id="cartBadge" style="{{ $cartCount > 0 ? '' : 'display:none' }}">{{ $cartCount }}</span>
    </button>
  </div>
</div>

<div class="shop-wrap">
  <!-- SIDEBAR -->
  <aside>
    <div class="filter-card">
      <div class="filter-header">
        Filters
        @if(request()->hasAny(['category','stock','min_price','max_price']))
          <a href="{{ route('shop.index') }}" class="btn-clear" style="margin:0;font-size:.72rem">Clear all</a>
        @endif
      </div>
      <div class="filter-body">
        <form method="GET" action="{{ route('shop.index') }}" id="filterForm">
          <input type="hidden" name="search" value="{{ request('search') }}">
          <input type="hidden" name="sort" value="{{ request('sort') }}">

          <div class="filter-section">
            <div class="filter-label">Category</div>
            @foreach($categories as $cat)
            <label class="filter-check">
              <input type="radio" name="category" value="{{ $cat }}" {{ request('category')===$cat?'checked':'' }} onchange="document.getElementById('filterForm').submit()">
              {{ $cat }}
            </label>
            @endforeach
            @if(request('category'))
            <label class="filter-check">
              <input type="radio" name="category" value="" onchange="document.getElementById('filterForm').submit()">
              All Categories
            </label>
            @endif
          </div>

          <div class="filter-section">
            <div class="filter-label">Availability</div>
            @foreach(['in_stock'=>'In Stock','low_stock'=>'Low Stock','out_of_stock'=>'Out of Stock'] as $val=>$lbl)
            <label class="filter-check">
              <input type="radio" name="stock" value="{{ $val }}" {{ request('stock')===$val?'checked':'' }} onchange="document.getElementById('filterForm').submit()">
              {{ $lbl }}
            </label>
            @endforeach
            @if(request('stock'))
            <label class="filter-check">
              <input type="radio" name="stock" value="" onchange="document.getElementById('filterForm').submit()">
              Show All
            </label>
            @endif
          </div>

          <div class="filter-section">
            <div class="filter-label">Price Range (₱)</div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:6px">
              <input class="filter-input" type="number" name="min_price" placeholder="Min" value="{{ request('min_price') }}" min="0">
              <input class="filter-input" type="number" name="max_price" placeholder="Max" value="{{ request('max_price') }}" min="0">
            </div>
            <button type="submit" class="btn-filter">Apply</button>
          </div>
        </form>
      </div>
    </div>
  </aside>

  <!-- MAIN -->
  <div>
    <!-- Toolbar -->
    <form method="GET" action="{{ route('shop.index') }}" class="shop-toolbar">
      <input type="hidden" name="category" value="{{ request('category') }}">
      <input type="hidden" name="stock" value="{{ request('stock') }}">
      <input type="hidden" name="min_price" value="{{ request('min_price') }}">
      <input type="hidden" name="max_price" value="{{ request('max_price') }}">
      <div class="search-wrap">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
        <input class="search-input" type="text" name="search" placeholder="Search products..." value="{{ request('search') }}">
      </div>
      <select class="sort-select" name="sort" onchange="this.form.submit()">
        <option value="newest" {{ request('sort','newest')==='newest'?'selected':'' }}>Newest</option>
        <option value="price_asc" {{ request('sort')==='price_asc'?'selected':'' }}>Price: Low to High</option>
        <option value="price_desc" {{ request('sort')==='price_desc'?'selected':'' }}>Price: High to Low</option>
        <option value="name" {{ request('sort')==='name'?'selected':'' }}>Name A–Z</option>
      </select>
    </form>

    <!-- Results info -->
    <div style="font-size:.78rem;color:var(--gray);margin-bottom:14px">
      Showing {{ $products->firstItem() ?? 0 }}–{{ $products->lastItem() ?? 0 }} of {{ $products->total() }} products
    </div>

    <!-- Grid -->
    <div class="product-grid">
      @forelse($products as $product)
      <div class="product-card">
        <a href="{{ route('shop.product', $product) }}" style="display:block;text-decoration:none">
          @if($product->image_url)
            <img class="product-img" src="{{ $product->image_url }}" alt="{{ $product->prod_name }}" loading="lazy">
          @else
            <div class="product-img-placeholder">
              <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="var(--border)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21 16V8a2 2 0 00-1-1.73l-7-4a2 2 0 00-2 0l-7 4A2 2 0 003 8v8a2 2 0 001 1.73l7 4a2 2 0 002 0l7-4A2 2 0 0021 16z"/></svg>
            </div>
          @endif
        </a>
        <div class="product-body">
          <div class="product-cat">{{ $product->category }}</div>
          <a href="{{ route('shop.product', $product) }}" style="text-decoration:none">
            <div class="product-name" style="color:var(--dark)">{{ $product->prod_name }}</div>
          </a>
          <div class="product-desc">{{ $product->prod_description }}</div>
          <div class="product-footer">
            <div>
              <div class="product-price">₱{{ number_format($product->price, 2) }}</div>
              <span class="stock-badge {{ $product->stock_level === 'in_stock' ? 'stock-in' : ($product->stock_level === 'low_stock' ? 'stock-low' : 'stock-out') }}">
                {{ str_replace('_', ' ', ucwords($product->stock_level, '_')) }}
              </span>
            </div>
            <div style="display:flex;flex-direction:column;gap:5px;align-items:flex-end">
              <a href="{{ route('shop.product', $product) }}" class="btn-detail">View</a>
              @if($product->variants->isNotEmpty())
                <button class="btn-add" onclick="openProductModal({{ $product->id }})"
                  {{ $product->stock_level === 'out_of_stock' ? 'disabled' : '' }}>
                  <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                  Add
                </button>
              @else
                <button class="btn-add" onclick="quickAdd({{ $product->id }}, null, this)"
                  {{ $product->stock_level === 'out_of_stock' ? 'disabled' : '' }}>
                  <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                  Add
                </button>
              @endif
            </div>
          </div>
        </div>
      </div>
      @empty
      <div class="empty-state">
        <div class="empty-icon">
          <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="var(--emerald)" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
        </div>
        <div style="font-size:1rem;font-weight:700;color:var(--dark);margin-bottom:8px">No products found</div>
        <div style="font-size:.83rem;color:var(--gray);margin-bottom:18px">Try adjusting your filters or search term.</div>
        <a href="{{ route('shop.index') }}" style="color:var(--forest);font-weight:600;font-size:.83rem">Clear filters</a>
      </div>
      @endforelse
    </div>

    <!-- Pagination -->
    @if($products->hasPages())
    <div style="margin-top:24px">{{ $products->withQueryString()->links() }}</div>
    @endif
  </div>
</div>

<!-- PRODUCT MODAL (variant picker) -->
<div id="productModal" style="display:none;position:fixed;inset:0;z-index:400;background:rgba(0,0,0,.5);align-items:center;justify-content:center" onclick="if(event.target===this)closeModal()">
  <div style="background:#fff;border-radius:18px;width:90%;max-width:480px;overflow:hidden;box-shadow:0 24px 64px rgba(0,0,0,.18)">
    <div id="modalContent" style="padding:24px"></div>
  </div>
</div>

<!-- CART DRAWER -->
<div class="cart-overlay" id="cartOverlay" onclick="closeCart()"></div>
<div class="cart-drawer" id="cartDrawer">
  <div class="cart-drawer-header">
    <div class="cart-drawer-title">Your Cart</div>
    <button class="cart-close" onclick="closeCart()">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
    </button>
  </div>
  <div class="cart-drawer-body" id="cartBody">
    <div class="cart-empty">Your cart is empty</div>
  </div>
  <div class="cart-drawer-footer" id="cartFooter" style="display:none">
    <div class="cart-total">
      <span class="cart-total-label">Total</span>
      <span class="cart-total-val" id="cartTotalVal">₱0.00</span>
    </div>
    <a href="{{ route('shop.cart') }}" class="btn-checkout">Proceed to Checkout</a>
  </div>
</div>

<!-- TOAST -->
<div class="toast" id="toast"></div>

@php $products_data = $products->map(fn($p) => [
    'id'       => $p->id,
    'name'     => $p->prod_name,
    'price'    => (float) $p->price,
    'image'    => $p->image_url,
    'stock'    => $p->stock_level,
    'variants' => $p->variants->map(fn($v) => [
        'id'       => $v->id,
        'name'     => $v->var_name,
        'modifier' => (float) $v->price_modifier,
        'stock'    => $v->stock_qty,
    ])->values()->all(),
])->values()->all(); @endphp
@endsection

@push('scripts')
<script>
const PRODUCTS = @json($products_data);
const CSRF = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
let cartItems = {};

function showToast(msg, type='success'){
  const t = document.getElementById('toast');
  t.textContent = msg; t.className = 'toast '+type;
  setTimeout(()=>t.classList.add('show'),10);
  setTimeout(()=>t.classList.remove('show'),3000);
}

function updateCartBadge(count){
  const b = document.getElementById('cartBadge');
  b.textContent = count; b.style.display = count > 0 ? 'flex' : 'none';
}

function addToCart(variantId, qty=1){
  return fetch("{{ route('shop.cart.add') }}", {
    method:'POST',
    headers:{'Content-Type':'application/json','X-CSRF-TOKEN':CSRF,'X-Requested-With':'XMLHttpRequest'},
    body: JSON.stringify({variant_id:variantId, quantity:qty})
  })
  .then(r=>r.json())
  .then(d=>{
    if(d.ok){ updateCartBadge(d.cartCount); showToast(d.message,'success'); }
    else showToast(d.message||'Error adding to cart','error');
    return d;
  });
}

function quickAdd(productId, variantId, btn){
  const p = PRODUCTS.find(x=>x.id===productId);
  if(!p) return;
  const vid = variantId || (p.variants[0]?.id);
  if(!vid){ showToast('No variant available','error'); return; }
  btn.disabled=true;
  addToCart(vid,1).finally(()=>{ btn.disabled=false; });
}

function openProductModal(productId){
  const p = PRODUCTS.find(x=>x.id===productId);
  if(!p) return;
  let html = `<div style="font-size:.78rem;font-weight:700;letter-spacing:1.5px;text-transform:uppercase;color:var(--emerald);margin-bottom:6px">${escHtml(p.name)}</div>`;
  html += `<div style="font-size:1.05rem;font-weight:700;color:var(--dark);margin-bottom:14px">Select a Variant</div>`;
  html += `<div style="display:flex;flex-direction:column;gap:8px" id="variantList">`;
  p.variants.forEach(v=>{
    const effectivePrice = p.price + v.modifier;
    const disabled = v.stock < 1;
    html += `<label style="display:flex;align-items:center;justify-content:space-between;padding:12px 14px;border:1.5px solid var(--border);border-radius:10px;cursor:${disabled?'not-allowed':'pointer'};opacity:${disabled?.5:1};transition:border-color .2s"
      onmouseover="if(!this.querySelector('input').disabled)this.style.borderColor='var(--emerald)'"
      onmouseout="if(!this.querySelector('input:checked'))this.style.borderColor='var(--border)'">
      <div style="display:flex;align-items:center;gap:10px">
        <input type="radio" name="variantPick" value="${v.id}" ${disabled?'disabled':''} style="accent-color:var(--forest)" onchange="document.getElementById('variantList').querySelectorAll('label').forEach(l=>l.style.borderColor='var(--border)');this.closest('label').style.borderColor='var(--forest)'">
        <div>
          <div style="font-size:.83rem;font-weight:600;color:var(--dark)">${escHtml(v.name)}</div>
          <div style="font-size:.7rem;color:var(--gray)">${disabled?'Out of stock':'In stock · '+v.stock+' available'}</div>
        </div>
      </div>
      <div style="font-size:.88rem;font-weight:700;color:var(--forest)">₱${effectivePrice.toLocaleString('en-PH',{minimumFractionDigits:2})}</div>
    </label>`;
  });
  html += `</div>`;
  html += `<div style="display:flex;gap:10px;margin-top:16px">
    <button onclick="closeModal()" style="flex:1;padding:10px;border-radius:10px;border:1.5px solid var(--border);background:#fff;font-family:inherit;cursor:pointer;font-size:.82rem;font-weight:600;color:var(--gray)">Cancel</button>
    <button onclick="addSelectedVariant(${productId})" style="flex:2;padding:10px;border-radius:10px;background:var(--forest);color:#fff;border:none;font-family:inherit;cursor:pointer;font-size:.82rem;font-weight:700">Add to Cart</button>
  </div>`;
  document.getElementById('modalContent').innerHTML = html;
  document.getElementById('productModal').style.display = 'flex';
}

function addSelectedVariant(productId){
  const sel = document.querySelector('input[name="variantPick"]:checked');
  if(!sel){ showToast('Please select a variant','error'); return; }
  addToCart(parseInt(sel.value),1).then(d=>{ if(d.ok) closeModal(); });
}

function closeModal(){
  document.getElementById('productModal').style.display='none';
}

function openCart(){
  document.getElementById('cartOverlay').classList.add('open');
  document.getElementById('cartDrawer').classList.add('open');
  loadCartDrawer();
}
function closeCart(){ document.getElementById('cartOverlay').classList.remove('open'); document.getElementById('cartDrawer').classList.remove('open'); }

const CART_DATA_URL = "{{ route('shop.cart.data') }}";
const IMG_PLACEHOLDER = '<div class="cart-item-img" style="display:flex;align-items:center;justify-content:center;background:var(--sand);border-radius:8px;width:52px;height:52px;flex-shrink:0"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="var(--border)" stroke-width="1.5"><rect x="3" y="3" width="18" height="18" rx="2"/></svg></div>';

function loadCartDrawer(){
  fetch(CART_DATA_URL, {headers:{'X-Requested-With':'XMLHttpRequest'}})
    .then(function(r){ return r.json(); })
    .then(function(data){
      var body   = document.getElementById('cartBody');
      var footer = document.getElementById('cartFooter');
      if(!data.items.length){
        body.innerHTML = '<div class="cart-empty">Your cart is empty</div>';
        footer.style.display = 'none';
        return;
      }
      var html = '';
      data.items.forEach(function(item){
        var imgHtml = item.image
          ? '<img class="cart-item-img" src="' + escHtml(item.image) + '" alt="">'
          : IMG_PLACEHOLDER;
        var price = parseFloat(item.unit_price).toLocaleString('en-PH', {minimumFractionDigits:2});
        html += '<div class="cart-item" id="di-' + item.variant_id + '">';
        html +=   imgHtml;
        html +=   '<div class="cart-item-info">';
        html +=     '<div class="cart-item-name">'    + escHtml(item.product_name) + '</div>';
        html +=     '<div class="cart-item-variant">' + escHtml(item.variant_name) + '</div>';
        html +=     '<div class="cart-item-price">&#8369;' + price + '</div>';
        html +=     '<div class="cart-item-actions">';
        html +=       '<button class="qty-btn" onclick="drawerQty(' + item.variant_id + ',-1)">&#8722;</button>';
        html +=       '<span class="qty-display" id="dq-' + item.variant_id + '">' + item.quantity + '</span>';
        html +=       '<button class="qty-btn" onclick="drawerQty(' + item.variant_id + ',1)">&#43;</button>';
        html +=       '<button class="cart-item-remove" onclick="drawerRemove(' + item.variant_id + ')">Remove</button>';
        html +=     '</div>';
        html +=   '</div>';
        html += '</div>';
      });
      body.innerHTML = html;
      document.getElementById('cartTotalVal').textContent = '₱' + parseFloat(data.total).toLocaleString('en-PH', {minimumFractionDigits:2});
      footer.style.display = 'block';
      updateCartBadge(data.count);
    })
    .catch(function(){});
}

function drawerQty(variantId, delta){
  const el  = document.getElementById('dq-'+variantId);
  const qty = Math.max(0, (parseInt(el?.textContent)||1) + delta);
  fetch('/cart/'+variantId, {
    method:'PATCH',
    headers:{'Content-Type':'application/json','X-CSRF-TOKEN':CSRF,'X-Requested-With':'XMLHttpRequest'},
    body: JSON.stringify({quantity:qty})
  }).then(r=>r.json()).then(d=>{ if(d.ok){ updateCartBadge(d.cartCount); loadCartDrawer(); } });
}

function drawerRemove(variantId){
  fetch('/cart/'+variantId, {
    method:'DELETE',
    headers:{'X-CSRF-TOKEN':CSRF,'X-Requested-With':'XMLHttpRequest'}
  }).then(r=>r.json()).then(d=>{ if(d.ok){ updateCartBadge(d.cartCount); loadCartDrawer(); } });
}

function escHtml(s){ const d=document.createElement('div'); d.textContent=s; return d.innerHTML; }
</script>
@endpush
