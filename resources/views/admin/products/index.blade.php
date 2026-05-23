@extends('layouts.admin')
@section('title','Products')

@push('styles')
<style>
.modal-overlay{position:fixed;inset:0;background:rgba(0,0,0,.45);z-index:500;display:none;align-items:center;justify-content:center}
.modal-overlay.open{display:flex}
.modal-box{background:#fff;border-radius:18px;width:90%;max-width:540px;overflow:hidden;box-shadow:0 24px 64px rgba(0,0,0,.18);max-height:90vh;display:flex;flex-direction:column}
.modal-header{padding:18px 24px;border-bottom:1px solid var(--border);display:flex;align-items:center;justify-content:space-between;flex-shrink:0}
.modal-title{font-size:.95rem;font-weight:700;color:var(--dark)}
.modal-close{background:none;border:none;cursor:pointer;color:var(--gray);padding:4px;display:flex}
.modal-close:hover{color:var(--dark)}
.modal-body{padding:24px;overflow-y:auto}
.modal-footer{padding:16px 24px;border-top:1px solid var(--border);display:flex;gap:10px;justify-content:flex-end;flex-shrink:0}

.form-group{display:flex;flex-direction:column;gap:5px;margin-bottom:14px}
.form-label{font-size:.75rem;font-weight:700;color:var(--dark);letter-spacing:.3px}
.form-control{padding:9px 12px;border-radius:9px;border:1.5px solid var(--border);font-size:.83rem;font-family:inherit;color:var(--dark);background:#fff;outline:none;transition:border-color .2s;width:100%}
.form-control:focus{border-color:var(--forest);box-shadow:0 0 0 3px rgba(51,106,41,.08)}
.form-control.error{border-color:#dc2626}
.form-error{font-size:.7rem;color:#dc2626}
.form-grid-2{display:grid;grid-template-columns:1fr 1fr;gap:12px}
textarea.form-control{resize:vertical;min-height:70px}

.product-img-preview{width:100%;height:140px;border-radius:10px;object-fit:cover;border:1px solid var(--border);margin-top:6px;display:none}
</style>
@endpush

@section('content')
<div class="page-header">
  <div>
    <h1 class="page-title">Products</h1>
    <p class="page-sub">Manage your product catalogue and inventory.</p>
  </div>
  <button class="btn btn-primary" onclick="openAddModal()">
    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
    Add Product
  </button>
</div>

@if(session('success'))
<div class="alert alert-success">{{ session('success') }}</div>
@endif
@if(session('error'))
<div class="alert alert-error">{{ session('error') }}</div>
@endif

<!-- Filters -->
<div class="card" style="margin-bottom:18px">
  <form method="GET" action="{{ route('admin.products.index') }}" style="display:flex;gap:10px;align-items:flex-end;flex-wrap:wrap;padding:16px 20px">
    <div style="flex:1;min-width:180px">
      <div class="form-label" style="margin-bottom:5px">Search</div>
      <input class="form-control" type="text" name="search" placeholder="Name or category..." value="{{ request('search') }}">
    </div>
    <div style="min-width:140px">
      <div class="form-label" style="margin-bottom:5px">Category</div>
      <select class="form-control form-select" name="category">
        <option value="">All Categories</option>
        @foreach($categories as $cat)
        <option value="{{ $cat }}" {{ request('category')===$cat?'selected':'' }}>{{ $cat }}</option>
        @endforeach
      </select>
    </div>
    <div style="min-width:120px">
      <div class="form-label" style="margin-bottom:5px">Stock</div>
      <select class="form-control form-select" name="stock">
        <option value="">All</option>
        <option value="in_stock" {{ request('stock')==='in_stock'?'selected':'' }}>In Stock</option>
        <option value="low_stock" {{ request('stock')==='low_stock'?'selected':'' }}>Low Stock</option>
        <option value="out_of_stock" {{ request('stock')==='out_of_stock'?'selected':'' }}>Out of Stock</option>
      </select>
    </div>
    <button type="submit" class="btn btn-primary" style="height:38px">Filter</button>
    @if(request()->hasAny(['search','category','stock']))
      <a href="{{ route('admin.products.index') }}" class="btn btn-secondary" style="height:38px">Clear</a>
    @endif
  </form>
</div>

<div class="card">
  <div class="tbl-wrap">
    <table class="tbl">
      <thead>
        <tr>
          <th>Product</th>
          <th>Category</th>
          <th>Price</th>
          <th>Stock</th>
          <th>Variants</th>
          <th>Status</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        @forelse($products as $product)
        <tr>
          <td>
            <div style="display:flex;align-items:center;gap:10px">
              @if($product->image_url)
                <img src="{{ $product->image_url }}" style="width:38px;height:38px;border-radius:8px;object-fit:cover;border:1px solid var(--border)">
              @else
                <div style="width:38px;height:38px;border-radius:8px;background:var(--sand);display:flex;align-items:center;justify-content:center;flex-shrink:0">
                  <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="var(--border)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21 16V8a2 2 0 00-1-1.73l-7-4a2 2 0 00-2 0l-7 4A2 2 0 003 8v8a2 2 0 001 1.73l7 4a2 2 0 002 0l7-4A2 2 0 0021 16z"/></svg>
                </div>
              @endif
              <div>
                <div style="font-size:.82rem;font-weight:700;color:var(--dark)">{{ $product->prod_name }}</div>
                <div style="font-size:.7rem;color:var(--gray)">ID #{{ $product->id }}</div>
              </div>
            </div>
          </td>
          <td><span style="font-size:.75rem;color:var(--gray)">{{ $product->category }}</span></td>
          <td><strong style="color:var(--dark)">₱{{ number_format($product->price, 2) }}</strong></td>
          <td>
            <div style="font-size:.82rem;font-weight:600;color:var(--dark)">{{ $product->stock_qty }}</div>
            <span class="badge {{ $product->stock_level === 'in_stock' ? 'badge-success' : ($product->stock_level === 'low_stock' ? 'badge-warning' : 'badge-danger') }}">
              {{ str_replace('_',' ',ucwords($product->stock_level,'_')) }}
            </span>
          </td>
          <td style="color:var(--gray);font-size:.82rem">{{ $product->variants_count }}</td>
          <td>
            <span class="badge {{ $product->is_active ? 'badge-success' : 'badge-secondary' }}">
              {{ $product->is_active ? 'Active' : 'Inactive' }}
            </span>
          </td>
          <td>
            <div style="display:flex;gap:6px">
              <button class="btn btn-secondary btn-sm" onclick="openEditModal({{ $product->id }})">Edit</button>
              <button class="btn btn-secondary btn-sm" onclick="openVariantsModal({{ $product->id }})">Variants</button>
              @if($product->is_active)
              <form method="POST" action="{{ route('admin.products.destroy', $product) }}" onsubmit="return confirm('Deactivate this product?')">
                @csrf @method('DELETE')
                <button type="submit" class="btn btn-danger btn-sm">Deactivate</button>
              </form>
              @endif
            </div>
          </td>
        </tr>
        @empty
        <tr><td colspan="7" style="text-align:center;padding:40px;color:var(--gray)">No products found.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
  @if($products->hasPages())
  <div style="padding:16px 20px;border-top:1px solid var(--border)">{{ $products->withQueryString()->links() }}</div>
  @endif
</div>

<!-- ── ADD PRODUCT MODAL ──────────────────────────────────────────────── -->
<div class="modal-overlay" id="addModal" onclick="if(event.target===this)closeAddModal()">
  <div class="modal-box">
    <div class="modal-header">
      <div class="modal-title">Add New Product</div>
      <button class="modal-close" onclick="closeAddModal()">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
      </button>
    </div>
    <form method="POST" action="{{ route('admin.products.store') }}" id="addForm" enctype="multipart/form-data">
      @csrf
      <div class="modal-body">
        <div class="form-group">
          <label class="form-label">Product Name *</label>
          <input class="form-control @error('prod_name') error @enderror" type="text" name="prod_name" value="{{ old('prod_name') }}" required>
          @error('prod_name')<div class="form-error">{{ $message }}</div>@enderror
        </div>
        <div class="form-group">
          <label class="form-label">Description</label>
          <textarea class="form-control" name="prod_description">{{ old('prod_description') }}</textarea>
        </div>
        <div class="form-grid-2">
          <div class="form-group">
            <label class="form-label">Base Price (₱) *</label>
            <input class="form-control @error('price') error @enderror" type="number" name="price" step="0.01" min="0" value="{{ old('price') }}" required>
            @error('price')<div class="form-error">{{ $message }}</div>@enderror
          </div>
          <div class="form-group">
            <label class="form-label">Stock Quantity *</label>
            <input class="form-control @error('stock_qty') error @enderror" type="number" name="stock_qty" min="0" value="{{ old('stock_qty', 0) }}" required>
            @error('stock_qty')<div class="form-error">{{ $message }}</div>@enderror
          </div>
        </div>
        <div class="form-group">
          <label class="form-label">Category *</label>
          <input class="form-control @error('category') error @enderror" type="text" name="category" value="{{ old('category') }}" placeholder="e.g., Solar, Biogas, Agriculture" required>
          @error('category')<div class="form-error">{{ $message }}</div>@enderror
        </div>
        <div class="form-group">
          <label class="form-label">Product Image</label>
          <div style="display:flex;gap:6px;margin-bottom:8px">
            <button type="button" onclick="setImgTab('add','url')" id="addTabUrl" class="btn btn-primary btn-sm" style="font-size:.72rem">URL</button>
            <button type="button" onclick="setImgTab('add','file')" id="addTabFile" class="btn btn-secondary btn-sm" style="font-size:.72rem">Upload File</button>
          </div>
          <div id="addImgUrl">
            <input class="form-control" type="url" name="image_url" value="{{ old('image_url') }}"
              placeholder="https://..." oninput="previewImg(this,'addPreview')">
          </div>
          <div id="addImgFile" style="display:none">
            <input class="form-control" type="file" name="image_file" accept="image/*" onchange="previewFile(this,'addPreview')">
          </div>
          <img id="addPreview" class="product-img-preview">
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" onclick="closeAddModal()">Cancel</button>
        <button type="submit" class="btn btn-primary">Add Product</button>
      </div>
    </form>
  </div>
</div>

<!-- ── EDIT PRODUCT MODAL ─────────────────────────────────────────────── -->
<div class="modal-overlay" id="editModal" onclick="if(event.target===this)closeEditModal()">
  <div class="modal-box">
    <div class="modal-header">
      <div class="modal-title">Edit Product</div>
      <button class="modal-close" onclick="closeEditModal()">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
      </button>
    </div>
    <form method="POST" id="editForm" enctype="multipart/form-data">
      @csrf @method('PUT')
      <div class="modal-body">
        <div class="form-group">
          <label class="form-label">Product Name *</label>
          <input class="form-control" type="text" name="prod_name" id="edit_prod_name" required>
        </div>
        <div class="form-group">
          <label class="form-label">Description</label>
          <textarea class="form-control" name="prod_description" id="edit_prod_description"></textarea>
        </div>
        <div class="form-grid-2">
          <div class="form-group">
            <label class="form-label">Base Price (₱) *</label>
            <input class="form-control" type="number" name="price" id="edit_price" step="0.01" min="0" required>
          </div>
          <div class="form-group">
            <label class="form-label">Stock Quantity *</label>
            <input class="form-control" type="number" name="stock_qty" id="edit_stock_qty" min="0" required>
          </div>
        </div>
        <div class="form-group">
          <label class="form-label">Category *</label>
          <input class="form-control" type="text" name="category" id="edit_category" required>
        </div>
        <div class="form-group">
          <label class="form-label">Product Image</label>
          <div style="display:flex;gap:6px;margin-bottom:8px">
            <button type="button" onclick="setImgTab('edit','url')" id="editTabUrl" class="btn btn-primary btn-sm" style="font-size:.72rem">URL</button>
            <button type="button" onclick="setImgTab('edit','file')" id="editTabFile" class="btn btn-secondary btn-sm" style="font-size:.72rem">Upload File</button>
          </div>
          <div id="editImgUrl">
            <input class="form-control" type="url" name="image_url" id="edit_image_url"
              oninput="previewImg(this,'editPreview')">
          </div>
          <div id="editImgFile" style="display:none">
            <input class="form-control" type="file" name="image_file" accept="image/*" onchange="previewFile(this,'editPreview')">
          </div>
          <img id="editPreview" class="product-img-preview">
        </div>
        <div class="form-group">
          <label class="form-label">Restock Reason</label>
          <input class="form-control" type="text" name="restock_reason" placeholder="Reason for stock change (if any)">
        </div>
        <div class="form-group">
          <label style="display:flex;align-items:center;gap:8px;cursor:pointer;font-size:.82rem;font-weight:600;color:var(--dark)">
            <input type="checkbox" name="is_active" id="edit_is_active" value="1" style="accent-color:var(--forest);width:15px;height:15px">
            Active (visible in shop)
          </label>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" onclick="closeEditModal()">Cancel</button>
        <button type="submit" class="btn btn-primary">Save Changes</button>
      </div>
    </form>
  </div>
</div>

<!-- ── VARIANTS MODAL ────────────────────────────────────────────────── -->
<div class="modal-overlay" id="variantsModal" onclick="if(event.target===this)closeVariantsModal()">
  <div class="modal-box" style="max-width:600px">
    <div class="modal-header">
      <div class="modal-title" id="variantsModalTitle">Variants</div>
      <button class="modal-close" onclick="closeVariantsModal()">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
      </button>
    </div>
    <div class="modal-body">
      <div id="variantsList" style="margin-bottom:20px"></div>
      <div style="font-size:.82rem;font-weight:700;color:var(--dark);margin-bottom:10px;padding-top:14px;border-top:1px solid var(--border)">Add New Variant</div>
      <form method="POST" id="addVariantForm">
        @csrf
        <div class="form-grid-2">
          <div class="form-group">
            <label class="form-label">Variant Name *</label>
            <input class="form-control" type="text" name="var_name" required placeholder="e.g., 100W Panel">
          </div>
          <div class="form-group">
            <label class="form-label">Price Modifier (₱)</label>
            <input class="form-control" type="number" name="price_modifier" step="0.01" value="0" placeholder="0.00">
          </div>
        </div>
        <div class="form-group">
          <label class="form-label">Stock Quantity *</label>
          <input class="form-control" type="number" name="stock_qty" min="0" value="0" required>
        </div>
        <div class="form-group">
          <label class="form-label">Description</label>
          <input class="form-control" type="text" name="description" placeholder="Optional">
        </div>
        <div class="form-group">
          <label class="form-label">Specification</label>
          <input class="form-control" type="text" name="specification" placeholder="Technical specs">
        </div>
        <button type="submit" class="btn btn-primary" style="width:100%">Add Variant</button>
      </form>
    </div>
  </div>
</div>

@php
$productsJson = $products->map(fn($p) => [
  'id'          => $p->id,
  'prod_name'   => $p->prod_name,
  'prod_description' => $p->prod_description,
  'price'       => (float) $p->price,
  'stock_qty'   => $p->stock_qty,
  'category'    => $p->category,
  'image_url'   => $p->image_url,
  'is_active'   => (bool) $p->is_active,
  'variants'    => $p->variants->map(fn($v) => [
    'id'       => $v->id,
    'var_name' => $v->var_name,
    'price_modifier' => (float) $v->price_modifier,
    'stock_qty'=> $v->stock_qty,
  ])->values()->all(),
])->keyBy('id')->all();
@endphp
@endsection

@push('scripts')
<script>
const PRODUCTS_MAP = @json($productsJson);

function previewImg(input, previewId){
  const img = document.getElementById(previewId);
  if(input.value){ img.src = input.value; img.style.display='block'; }
  else img.style.display='none';
}

function previewFile(input, previewId){
  const img = document.getElementById(previewId);
  if(input.files && input.files[0]){
    const reader = new FileReader();
    reader.onload = e => { img.src = e.target.result; img.style.display='block'; };
    reader.readAsDataURL(input.files[0]);
  } else img.style.display='none';
}

function setImgTab(prefix, tab){
  const urlDiv  = document.getElementById(prefix+'ImgUrl');
  const fileDiv = document.getElementById(prefix+'ImgFile');
  const urlBtn  = document.getElementById(prefix+'TabUrl');
  const fileBtn = document.getElementById(prefix+'TabFile');
  if(tab === 'url'){
    urlDiv.style.display=''; fileDiv.style.display='none';
    urlBtn.className='btn btn-primary btn-sm'; fileBtn.className='btn btn-secondary btn-sm';
    // clear file input so it doesn't override URL
    fileDiv.querySelector('input[type=file]').value='';
  } else {
    urlDiv.style.display='none'; fileDiv.style.display='';
    urlBtn.className='btn btn-secondary btn-sm'; fileBtn.className='btn btn-primary btn-sm';
    // clear url input so it doesn't override file
    urlDiv.querySelector('input[type=url]').value='';
    document.getElementById(prefix+'Preview').style.display='none';
  }
}

// ── Add ──
function openAddModal(){
  @if($errors->any())
  // keep open if validation failed
  @endif
  document.getElementById('addModal').classList.add('open');
}
function closeAddModal(){ document.getElementById('addModal').classList.remove('open'); }

// ── Edit ──
function openEditModal(id){
  const p = PRODUCTS_MAP[id];
  if(!p) return;
  document.getElementById('edit_prod_name').value = p.prod_name;
  document.getElementById('edit_prod_description').value = p.prod_description || '';
  document.getElementById('edit_price').value = p.price;
  document.getElementById('edit_stock_qty').value = p.stock_qty;
  document.getElementById('edit_category').value = p.category;
  document.getElementById('edit_image_url').value = p.image_url || '';
  document.getElementById('edit_is_active').checked = p.is_active;
  const preview = document.getElementById('editPreview');
  if(p.image_url){ preview.src=p.image_url; preview.style.display='block'; }
  else preview.style.display='none';

  const routeBase = "{{ url('admin/products') }}";
  document.getElementById('editForm').action = routeBase+'/'+id;
  document.getElementById('editModal').classList.add('open');
}
function closeEditModal(){ document.getElementById('editModal').classList.remove('open'); }

// ── Variants ──
function openVariantsModal(id){
  const p = PRODUCTS_MAP[id];
  if(!p) return;
  document.getElementById('variantsModalTitle').textContent = 'Variants — '+p.prod_name;

  let html = '';
  if(p.variants.length === 0){
    html = '<div style="font-size:.82rem;color:var(--gray);text-align:center;padding:14px">No variants yet.</div>';
  } else {
    html = '<div style="display:flex;flex-direction:column;gap:8px">';
    p.variants.forEach(v=>{
      html += `<div style="display:flex;align-items:center;justify-content:space-between;padding:10px 14px;border:1px solid var(--border);border-radius:10px">
        <div>
          <div style="font-size:.82rem;font-weight:700;color:var(--dark)">${escHtml(v.var_name)}</div>
          <div style="font-size:.72rem;color:var(--gray)">Stock: ${v.stock_qty} · Modifier: ${v.price_modifier >= 0 ? '+' : ''}₱${parseFloat(v.price_modifier).toFixed(2)}</div>
        </div>
        <form method="POST" action="{{ url('admin/variants') }}/${v.id}" onsubmit="return confirm('Remove this variant?')">
          <input type="hidden" name="_token" value="{{ csrf_token() }}">
          <input type="hidden" name="_method" value="DELETE">
          <button type="submit" style="background:none;border:none;cursor:pointer;color:var(--gray);font-size:.72rem;font-weight:600;transition:color .2s" onmouseover="this.style.color='#dc2626'" onmouseout="this.style.color='var(--gray)'">Remove</button>
        </form>
      </div>`;
    });
    html += '</div>';
  }
  document.getElementById('variantsList').innerHTML = html;

  const routeBase = "{{ url('admin/products') }}";
  document.getElementById('addVariantForm').action = routeBase+'/'+id+'/variants';
  document.getElementById('variantsModal').classList.add('open');
}
function closeVariantsModal(){ document.getElementById('variantsModal').classList.remove('open'); }

function escHtml(s){ const d=document.createElement('div'); d.textContent=s; return d.innerHTML; }

// Re-open add modal if validation errors
@if($errors->any() && old('_method') === null)
openAddModal();
@endif
</script>
@endpush
