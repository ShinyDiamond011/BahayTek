<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Inventory;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::withCount('variants')->orderByDesc('added_at');

        if ($request->filled('search')) {
            $s = '%' . $request->search . '%';
            $query->where(fn($q) => $q->where('prod_name', 'like', $s)->orWhere('category', 'like', $s));
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('stock')) {
            $query->where('stock_level', $request->stock);
        }

        $products   = $query->paginate(15)->withQueryString();
        $categories = Product::distinct()->pluck('category')->sort()->values();

        return view('admin.products.index', compact('products', 'categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'prod_name'        => 'required|string|max:255',
            'prod_description' => 'nullable|string',
            'price'            => 'required|numeric|min:0',
            'stock_qty'        => 'required|integer|min:0',
            'category'         => 'required|string|max:100',
            'image_url'        => 'nullable|string|max:500',
            'image_file'       => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('image_file')) {
            $validated['image_url'] = Storage::disk('public')->url(
                $request->file('image_file')->store('products', 'public')
            );
        }

        unset($validated['image_file']);

        $validated['stock_level'] = match(true) {
            (int)$validated['stock_qty'] === 0  => 'out_of_stock',
            (int)$validated['stock_qty'] <= 10  => 'low_stock',
            default                             => 'in_stock',
        };

        Product::create($validated);

        return back()->with('success', 'Product added successfully.');
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'prod_name'        => 'required|string|max:255',
            'prod_description' => 'nullable|string',
            'price'            => 'required|numeric|min:0',
            'stock_qty'        => 'required|integer|min:0',
            'category'         => 'required|string|max:100',
            'image_url'        => 'nullable|string|max:500',
            'image_file'       => 'nullable|image|max:2048',
            'is_active'        => 'boolean',
        ]);

        if ($request->hasFile('image_file')) {
            $validated['image_url'] = Storage::disk('public')->url(
                $request->file('image_file')->store('products', 'public')
            );
        }
        unset($validated['image_file']);

        $oldQty = $product->stock_qty;
        $newQty = (int) $validated['stock_qty'];

        $validated['stock_level'] = match(true) {
            $newQty === 0  => 'out_of_stock',
            $newQty <= 10  => 'low_stock',
            default        => 'in_stock',
        };

        $validated['is_active'] = $request->boolean('is_active');

        $product->update($validated);

        // Log inventory change if stock quantity changed
        if ($oldQty !== $newQty) {
            Inventory::create([
                'product_id'     => $product->id,
                'admin_id'       => Auth::guard('staff')->id(),
                'qty_before'     => $oldQty,
                'qty_changed'    => $newQty - $oldQty,
                'qty_after'      => $newQty,
                'reason'         => $request->input('restock_reason', 'Manual update via admin panel'),
                'date_restocked' => now(),
                'recorded_at'    => now(),
            ]);
        }

        return back()->with('success', 'Product updated.');
    }

    public function destroy(Product $product)
    {
        $product->update(['is_active' => false]);
        return back()->with('success', 'Product deactivated.');
    }

    // ── Variants ──────────────────────────────────────────────────────────────

    public function storeVariant(Request $request, Product $product)
    {
        $validated = $request->validate([
            'var_name'      => 'required|string|max:255',
            'description'   => 'nullable|string',
            'specification' => 'nullable|string',
            'price_modifier'=> 'nullable|numeric',
            'stock_qty'     => 'required|integer|min:0',
        ]);

        $product->variants()->create($validated);

        return back()->with('success', 'Variant added.');
    }

    public function destroyVariant(ProductVariant $variant)
    {
        $variant->delete();
        return back()->with('success', 'Variant removed.');
    }
}
