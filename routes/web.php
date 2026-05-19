<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\UserAuthController;
use App\Http\Controllers\Auth\StaffAuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ConsultationController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\WorkshopController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\BookingController as AdminBookingController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\WorkshopController as AdminWorkshopController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\ReportController;

// ─── Public ───────────────────────────────────────────────────────────────────
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::get('/services', [HomeController::class, 'services'])->name('services');

// ─── User Auth ────────────────────────────────────────────────────────────────
Route::middleware('guest')->group(function () {
    Route::get('/login',    [UserAuthController::class, 'showLogin'])->name('login');
    Route::post('/login',   [UserAuthController::class, 'login']);
    Route::get('/register', [UserAuthController::class, 'showRegister'])->name('register');
    Route::post('/register',[UserAuthController::class, 'register']);
});
Route::post('/logout', [UserAuthController::class, 'logout'])->name('logout')->middleware('auth.user');

// ─── Consultation (Scheduling) ────────────────────────────────────────────────
Route::get('/consultation', [ConsultationController::class, 'index'])->name('consultation.index');
Route::get('/consultation/slots', [ConsultationController::class, 'slots'])->name('consultation.slots');
Route::middleware('auth.user')->group(function () {
    Route::post('/consultation/book',                          [ConsultationController::class, 'book'])->name('consultation.book');
    Route::get('/consultation/my-bookings',                    [ConsultationController::class, 'myBookings'])->name('consultation.my-bookings');
    Route::delete('/consultation/{booking}',                   [ConsultationController::class, 'cancel'])->name('consultation.cancel');
});

// ─── Shop (E-Commerce) ────────────────────────────────────────────────────────
Route::get('/shop', [ShopController::class, 'index'])->name('shop.index');
Route::get('/shop/product/{product}', [ShopController::class, 'show'])->name('shop.product');
// Cart — no auth needed (guests can add/view/update cart)
Route::get('/cart',                [ShopController::class, 'cart'])->name('shop.cart');
Route::get('/cart/data',           [ShopController::class, 'cartData'])->name('shop.cart.data');
Route::post('/cart/add',           [ShopController::class, 'addToCart'])->name('shop.cart.add');
Route::patch('/cart/{item}',       [ShopController::class, 'updateCart'])->name('shop.cart.update');
Route::delete('/cart/{item}',      [ShopController::class, 'removeFromCart'])->name('shop.cart.remove');

// Checkout & orders — auth required
Route::middleware('auth.user')->group(function () {
    Route::get('/checkout',            [ShopController::class, 'checkout'])->name('shop.checkout');
    Route::post('/checkout',           [ShopController::class, 'placeOrder'])->name('shop.order.place');
    Route::get('/orders',              [ShopController::class, 'myOrders'])->name('shop.orders');
    Route::get('/orders/{order}',      [ShopController::class, 'orderDetail'])->name('shop.order.detail');
    Route::get('/payment/{order}/success', [ShopController::class, 'paymentSuccess'])->name('shop.payment.success');
    Route::get('/payment/{order}/cancel',  [ShopController::class, 'paymentCancel'])->name('shop.payment.cancel');
});

// ─── PayMongo Webhook (no auth, signed) ──────────────────────────────────────
Route::post('/webhooks/paymongo', [ShopController::class, 'handleWebhook'])->name('webhooks.paymongo');

// ─── Workshops ────────────────────────────────────────────────────────────────
Route::get('/workshops', [WorkshopController::class, 'index'])->name('workshops.index');
Route::middleware('auth.user')->group(function () {
    Route::post('/workshops/{session}/enroll', [WorkshopController::class, 'enroll'])->name('workshops.enroll');
    Route::get('/workshops/my-enrollments',    [WorkshopController::class, 'myEnrollments'])->name('workshops.my-enrollments');
});

// ─── Admin Auth ───────────────────────────────────────────────────────────────
Route::prefix('admin')->name('admin.')->group(function () {
    Route::middleware('guest:staff')->group(function () {
        Route::get('/login',  [StaffAuthController::class, 'showLogin'])->name('login');
        Route::post('/login', [StaffAuthController::class, 'login']);
    });
    Route::post('/logout', [StaffAuthController::class, 'logout'])->name('logout')->middleware('auth.staff');

    // ─── Admin Protected ──────────────────────────────────────────────────────
    Route::middleware('auth.staff')->group(function () {
        Route::get('/',                               [DashboardController::class, 'index'])->name('dashboard');

        // Consultation Bookings
        Route::get('/bookings',                         [AdminBookingController::class, 'index'])->name('bookings.index');
        Route::get('/bookings/{booking}',               [AdminBookingController::class, 'show'])->name('bookings.show');
        Route::patch('/bookings/{booking}/status',      [AdminBookingController::class, 'updateStatus'])->name('bookings.status');
        // Schedule Slot Management
        Route::get('/schedules',                        [AdminBookingController::class, 'schedules'])->name('schedules.index');
        Route::post('/schedules',                       [AdminBookingController::class, 'createSlot'])->name('schedules.store');
        Route::delete('/schedules/{schedule}',          [AdminBookingController::class, 'deleteSlot'])->name('schedules.destroy');

        // Orders
        Route::get('/orders',                         [AdminOrderController::class, 'index'])->name('orders.index');
        Route::get('/orders/{order}',                 [AdminOrderController::class, 'show'])->name('orders.show');
        Route::patch('/orders/{order}/status',        [AdminOrderController::class, 'updateStatus'])->name('orders.status');

        // Workshops
        Route::get('/workshops',                                                  [AdminWorkshopController::class, 'index'])->name('workshops.index');
        Route::get('/workshops/create',                                           [AdminWorkshopController::class, 'create'])->name('workshops.create');
        Route::post('/workshops',                                                 [AdminWorkshopController::class, 'store'])->name('workshops.store');
        Route::get('/workshops/{session}',                                        [AdminWorkshopController::class, 'show'])->name('workshops.show');
        Route::put('/workshops/{session}',                                        [AdminWorkshopController::class, 'update'])->name('workshops.update');
        Route::delete('/workshops/{session}',                                     [AdminWorkshopController::class, 'destroy'])->name('workshops.destroy');
        Route::patch('/registrations/{registration}/status',                      [AdminWorkshopController::class, 'updateRegistration'])->name('registrations.status');

        // Products & Inventory
        Route::get('/products',                           [AdminProductController::class, 'index'])->name('products.index');
        Route::post('/products',                          [AdminProductController::class, 'store'])->name('products.store');
        Route::put('/products/{product}',                 [AdminProductController::class, 'update'])->name('products.update');
        Route::delete('/products/{product}',              [AdminProductController::class, 'destroy'])->name('products.destroy');
        Route::post('/products/{product}/variants',       [AdminProductController::class, 'storeVariant'])->name('products.variants.store');
        Route::delete('/variants/{variant}',              [AdminProductController::class, 'destroyVariant'])->name('products.variants.destroy');

        // Users
        Route::get('/users',                          [AdminUserController::class, 'index'])->name('users.index');
        Route::get('/users/{user}',                   [AdminUserController::class, 'show'])->name('users.show');
        Route::patch('/users/{user}/status',          [AdminUserController::class, 'updateStatus'])->name('users.status');

        // Reports
        Route::get('/reports',                        [ReportController::class, 'index'])->name('reports.index');
        Route::get('/reports/export',                 [ReportController::class, 'export'])->name('reports.export');
    });
});
