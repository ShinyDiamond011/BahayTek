<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductReview;
use App\Models\ProductVariant;
use App\Services\PaymongoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ShopController extends Controller
{
    // ── Public Shop ───────────────────────────────────────────────────────────

    public function index(Request $request)
    {
        $query = Product::with('variants')->where('is_active', true)->has('variants');

        if ($request->filled('search')) {
            $s = '%' . $request->search . '%';
            $query->where(fn($q) => $q->where('prod_name', 'like', $s)->orWhere('prod_description', 'like', $s));
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('stock')) {
            $query->where('stock_level', $request->stock);
        }

        if ($request->filled('min_price')) {
            $query->where('price', '>=', (float) $request->min_price);
        }

        if ($request->filled('max_price')) {
            $query->where('price', '<=', (float) $request->max_price);
        }

        match($request->get('sort', 'newest')) {
            'price_asc'  => $query->orderBy('price'),
            'price_desc' => $query->orderByDesc('price'),
            'name'       => $query->orderBy('prod_name'),
            default      => $query->orderByDesc('added_at'),
        };

        $products   = $query->paginate(12)->withQueryString();
        $categories = Product::where('is_active', true)->distinct()->pluck('category')->sort()->values();
        $cartCount  = array_sum(session('cart', []));

        return view('shop.index', compact('products', 'categories', 'cartCount'));
    }

    public function show(Product $product)
    {
        abort_if(!$product->is_active, 404);
        $product->load('variants');
        abort_if($product->variants->isEmpty(), 404);
        $reviews   = ProductReview::with('user')->where('product_id', $product->id)->latest()->get();
        $userReview = Auth::check()
            ? $reviews->firstWhere('user_id', Auth::id())
            : null;
        $avgRating = $reviews->avg('rating');
        $cartCount = array_sum(session('cart', []));
        return view('shop.show', compact('product', 'cartCount', 'reviews', 'userReview', 'avgRating'));
    }

    public function storeReview(Request $request, Product $product)
    {
        abort_if(!$product->is_active, 404);

        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'title'  => 'nullable|string|max:150',
            'body'   => 'nullable|string|max:2000',
        ]);

        ProductReview::updateOrCreate(
            ['product_id' => $product->id, 'user_id' => Auth::id()],
            $validated
        );

        ActivityLog::record(
            'product_reviewed',
            Auth::user()->first_name . ' ' . Auth::user()->last_name . ' reviewed "' . $product->prod_name . '"',
            Auth::id(), null,
            ['product_id' => $product->id, 'rating' => $validated['rating']],
            $request->ip()
        );

        return back()->with('success', 'Your review has been saved.');
    }

    // ── Cart (session-based) ──────────────────────────────────────────────────

    public function cart()
    {
        [$items, $total] = $this->resolveCart();
        $cartCount = array_sum(array_column($items, 'quantity'));
        return view('shop.cart', compact('items', 'total', 'cartCount'));
    }

    public function cartData()
    {
        [$items, $total] = $this->resolveCart();
        return response()->json([
            'items' => array_map(fn($item) => [
                'variant_id'   => $item['variant_id'],
                'product_name' => $item['product']->prod_name,
                'variant_name' => $item['variant']->var_name,
                'unit_price'   => $item['unit_price'],
                'quantity'     => $item['quantity'],
                'subtotal'     => $item['subtotal'],
                'image'        => $item['product']->image_url,
            ], array_values($items)),
            'total' => $total,
            'count' => array_sum(array_column($items, 'quantity')),
        ]);
    }

    public function addToCart(Request $request)
    {
        $request->validate([
            'variant_id' => 'required|exists:product_variants,id',
            'quantity'   => 'required|integer|min:1|max:99',
        ]);

        $variant = ProductVariant::findOrFail($request->variant_id);

        if ($variant->stock_qty < 1) {
            return response()->json(['ok' => false, 'message' => 'Out of stock.'], 422);
        }

        $cart       = session('cart', []);
        $id         = (string) $request->variant_id;
        $cart[$id]  = min(($cart[$id] ?? 0) + (int) $request->quantity, $variant->stock_qty);
        session(['cart' => $cart]);

        return response()->json([
            'ok'        => true,
            'cartCount' => array_sum($cart),
            'message'   => 'Added to cart!',
        ]);
    }

    public function updateCart(Request $request, $item)
    {
        $request->validate(['quantity' => 'required|integer|min:0|max:99']);

        $cart = session('cart', []);
        $id   = (string) $item;

        if ((int) $request->quantity === 0) {
            unset($cart[$id]);
        } else {
            $variant = ProductVariant::find($item);
            if (!$variant) return response()->json(['ok' => false], 404);
            $cart[$id] = min((int) $request->quantity, $variant->stock_qty);
        }

        session(['cart' => $cart]);
        [, $total] = $this->resolveCart();

        return response()->json([
            'ok'        => true,
            'cartCount' => array_sum($cart),
            'total'     => number_format($total, 2),
        ]);
    }

    public function removeFromCart($item)
    {
        $cart = session('cart', []);
        unset($cart[(string) $item]);
        session(['cart' => $cart]);

        return response()->json(['ok' => true, 'cartCount' => array_sum($cart)]);
    }

    // ── Checkout & Orders ─────────────────────────────────────────────────────

    public function checkout()
    {
        [$items, $total] = $this->resolveCart();

        if (empty($items)) {
            return redirect()->route('shop.cart')->with('error', 'Your cart is empty.');
        }

        $cartCount = array_sum(array_column($items, 'quantity'));
        $user      = Auth::user();
        return view('shop.checkout', compact('items', 'total', 'cartCount', 'user'));
    }

    public function placeOrder(Request $request)
    {
        $validated = $request->validate([
            'first_name'     => 'required|string|max:100',
            'last_name'      => 'required|string|max:100',
            'email'          => 'required|email|max:255',
            'phone'          => 'required|string|max:20',
            'street_address' => 'required|string|max:500',
            'city'           => 'required|string|max:100',
            'province'       => 'required|string|max:100',
            'payment_method' => 'required|in:cod,gcash,paymongo',
            'notes'          => 'nullable|string|max:500',
        ]);

        [$items, $total] = $this->resolveCart();

        if (empty($items)) {
            return redirect()->route('shop.cart')->with('error', 'Your cart is empty.');
        }

        $order = DB::transaction(function () use ($validated, $items, $total) {
            $order = Order::create([
                'user_id'          => Auth::id(),
                'total_amount'     => $total,
                'status'           => 'pending',
                'payment_status'   => 'unpaid',
                'payment_method'   => $validated['payment_method'],
                'shipping_address' => json_encode([
                    'first_name' => $validated['first_name'],
                    'last_name'  => $validated['last_name'],
                    'email'      => $validated['email'],
                    'phone'      => $validated['phone'],
                    'street'     => $validated['street_address'],
                    'city'       => $validated['city'],
                    'province'   => $validated['province'],
                ]),
                'notes' => $validated['notes'] ?? null,
            ]);

            foreach ($items as $item) {
                OrderItem::create([
                    'order_id'   => $order->id,
                    'variant_id' => $item['variant_id'],
                    'quantity'   => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                ]);

                $variant = ProductVariant::find($item['variant_id']);
                if ($variant) {
                    $newQty = max(0, $variant->stock_qty - $item['quantity']);
                    $variant->update(['stock_qty' => $newQty]);
                    if ($variant->product) {
                        $variant->product->decrement('stock_qty', $item['quantity']);
                        $variant->product->updateStockLevel();
                    }
                }
            }

            session()->forget('cart');
            return $order;
        });

        ActivityLog::record(
            'order_placed',
            'Order placed: #' . str_pad($order->id, 5, '0', STR_PAD_LEFT) . ' (₱' . number_format($order->total_amount, 2) . ')',
            Auth::id(), null,
            ['order_id' => $order->id, 'total' => (float) $order->total_amount, 'method' => $order->payment_method],
            request()->ip()
        );

        $orderNumber = '#' . str_pad($order->id, 5, '0', STR_PAD_LEFT);

        if ($validated['payment_method'] === 'paymongo') {
            try {
                $order->load('items.variant.product');
                $result = (new PaymongoService())->createCheckoutSession($order);

                $order->update(['paymongo_payment_id' => $result['session_id']]);

                return redirect($result['checkout_url']);
            } catch (\Exception $e) {
                Log::error('PayMongo redirect failed', ['order_id' => $order->id, 'error' => $e->getMessage()]);
                return redirect()->route('shop.order.detail', $order)
                    ->with('error', 'Order ' . $orderNumber . ' placed, but the payment page failed to load. Please contact us to complete payment.');
            }
        }

        return redirect()->route('shop.orders')
            ->with('success', 'Order ' . $orderNumber . ' placed! We will process it shortly.');
    }

    public function myOrders()
    {
        $orders = Order::where('user_id', Auth::id())
            ->with('items.variant.product')
            ->orderByDesc('ordered_at')
            ->paginate(10);

        return view('shop.orders', compact('orders'));
    }

    public function orderDetail(Order $order)
    {
        abort_if($order->user_id !== Auth::id(), 403);
        $order->load('items.variant.product');
        return view('shop.order-detail', compact('order'));
    }

    public function paymentSuccess(Order $order)
    {
        abort_if($order->user_id !== Auth::id(), 403);
        $orderNumber = '#' . str_pad($order->id, 5, '0', STR_PAD_LEFT);
        return redirect()->route('shop.order.detail', $order)
            ->with('success', 'Payment for order ' . $orderNumber . ' is being processed. You will receive a confirmation once verified.');
    }

    public function paymentCancel(Order $order)
    {
        abort_if($order->user_id !== Auth::id(), 403);
        $orderNumber = '#' . str_pad($order->id, 5, '0', STR_PAD_LEFT);
        return redirect()->route('shop.order.detail', $order)
            ->with('error', 'Payment for order ' . $orderNumber . ' was cancelled. Your order remains pending — contact us if you need help.');
    }

    public function handleWebhook(Request $request)
    {
        $rawPayload = $request->getContent();
        $sigHeader  = $request->header('Paymongo-Signature', '');

        $paymongo = new PaymongoService();

        if (!$paymongo->verifyWebhookSignature($rawPayload, $sigHeader)) {
            Log::warning('PayMongo webhook: invalid signature');
            return response()->json(['error' => 'Invalid signature'], 401);
        }

        $event   = json_decode($rawPayload, true);
        $type    = $event['data']['attributes']['type'] ?? '';
        $payload = $event['data']['attributes']['data'] ?? [];

        if ($type === 'checkout_session.payment.paid') {
            $orderId = $payload['attributes']['metadata']['order_id'] ?? null;
            if ($orderId) {
                $order = Order::find((int) $orderId);
                if ($order && $order->payment_status !== 'paid') {
                    $order->update([
                        'payment_status' => 'paid',
                        'status'         => 'confirmed',
                    ]);
                    Log::info('PayMongo payment confirmed', ['order_id' => $orderId]);
                }
            }
        }

        return response()->json(['received' => true]);
    }

    // ── Helper ────────────────────────────────────────────────────────────────

    private function resolveCart(): array
    {
        $cart = session('cart', []);
        if (empty($cart)) return [[], 0];

        $variants = ProductVariant::with('product')
            ->whereIn('id', array_keys($cart))
            ->get()->keyBy('id');

        $items = [];
        $total = 0.0;

        foreach ($cart as $variantId => $qty) {
            $variant = $variants[(int) $variantId] ?? null;
            if (!$variant?->product) continue;

            $unitPrice = (float) $variant->product->price + (float) $variant->price_modifier;
            $subtotal  = $unitPrice * $qty;
            $total    += $subtotal;

            $items[] = [
                'variant_id' => (int) $variantId,
                'variant'    => $variant,
                'product'    => $variant->product,
                'quantity'   => $qty,
                'unit_price' => $unitPrice,
                'subtotal'   => $subtotal,
            ];
        }

        return [$items, $total];
    }
}
