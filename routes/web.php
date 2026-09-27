<?php

use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Customer\FarmerController as CustomerFarmerController;
use App\Http\Controllers\Customer\FavoriteController as CustomerFavoriteController;
use App\Http\Controllers\Customer\MarketController as CustomerMarketController;
use App\Http\Controllers\Customer\NotificationController as CustomerNotificationController;
use App\Http\Controllers\Customer\ProductController as CustomerProductController;
use App\Http\Controllers\Farmer\DashboardController;
use App\Http\Controllers\Farmer\MarketController;
use App\Http\Controllers\Farmer\OrderController;
use App\Http\Controllers\Farmer\PickupSlotController;
use App\Http\Controllers\Farmer\ProductController;
use App\Http\Controllers\Farmer\ReviewController;
use App\Http\Controllers\FarmerProfileController;
use App\Http\Controllers\ProfileController as UserProfileController;
use Illuminate\Support\Facades\Route;

// Home
Route::get('/', function () {
    return view('welcome');
});

// Role-based dashboard redirect
Route::get('/dashboard', function () {
    return match (auth()->user()->role) {
        'admin' => redirect()->route('admin.dashboard'),
        'farmer' => redirect()->route('farmer.dashboard'),
        'customer' => redirect()->route('customer.dashboard'),
        default => redirect('/'),
    };
})->middleware(['auth', 'verified'])->name('dashboard');

// General user profile
Route::middleware('auth')->group(function () {
    Route::get('/profile', [UserProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [UserProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [UserProfileController::class, 'destroy'])
        ->name('profile.destroy');
});

// Farmer profile creation
Route::middleware(['auth', 'role:farmer'])->group(function () {
    Route::get('/farmer/profile/create', [FarmerProfileController::class, 'create'])
        ->name('farmer.profile.create');

    Route::post('/farmer/profile', [FarmerProfileController::class, 'store'])
        ->name('farmer.profile.store');
});

// Admin dashboard
Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])
        ->name('admin.dashboard');

    Route::get('/farmers', [UserController::class, 'farmers'])
        ->name('admin.farmers.index');
    Route::patch('/farmers/{farmer}/approve', [UserController::class, 'approve'])
        ->name('admin.farmers.approve');
    Route::patch('/farmers/{farmer}/suspend', [UserController::class, 'suspend'])
        ->name('admin.farmers.suspend');

    Route::get('/users', [UserController::class, 'index'])
        ->name('admin.users.index');
});

// Farmer routes
Route::middleware(['auth', 'role:farmer'])->prefix('farmer')->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('farmer.dashboard');

    // Profile
    Route::get('/profile', [FarmerProfileController::class, 'edit'])
        ->name('farmer.profile');

    Route::put('/profile', [FarmerProfileController::class, 'update'])
        ->name('farmer.profile.update');

    // Markets
    Route::get('/markets', [MarketController::class, 'index'])
        ->name('farmer.markets.index');

    Route::get('/markets/create', [MarketController::class, 'create'])
        ->name('farmer.markets.create');

    Route::post('/markets', [MarketController::class, 'store'])
        ->name('farmer.markets.store');

    Route::delete('/markets/{market}', [MarketController::class, 'destroy'])
        ->name('farmer.markets.destroy');

    // Pickup slots
    Route::get('/pickup-slots', [PickupSlotController::class, 'index'])
        ->name('farmer.pickup-slots.index');

    Route::get('/pickup-slots/create', [PickupSlotController::class, 'create'])
        ->name('farmer.pickup-slots.create');

    Route::post('/pickup-slots', [PickupSlotController::class, 'store'])
        ->name('farmer.pickup-slots.store');

    Route::get('/pickup-slots/{pickupSlot}/edit', [PickupSlotController::class, 'edit'])
        ->name('farmer.pickup-slots.edit');

    Route::put('/pickup-slots/{pickupSlot}', [PickupSlotController::class, 'update'])
        ->name('farmer.pickup-slots.update');

    Route::delete('/pickup-slots/{pickupSlot}', [PickupSlotController::class, 'destroy'])
        ->name('farmer.pickup-slots.destroy');

    // Orders
    Route::get('/orders', [OrderController::class, 'index'])
        ->name('farmer.orders.index');

    Route::get('/orders/{order}', [OrderController::class, 'show'])
        ->name('farmer.orders.show');

    Route::patch('/orders/{order}/status', [OrderController::class, 'updateStatus'])
        ->name('farmer.orders.update-status');

    Route::get('/orders/history', [OrderController::class, 'history'])
        ->name('farmer.orders.history');

    // Reviews
    Route::get('/reviews', [ReviewController::class, 'index'])
        ->name('farmer.reviews.index');

    Route::patch('/reviews/{review}/respond', [ReviewController::class, 'respond'])
        ->name('farmer.reviews.respond');

    // Products
    Route::get('/products', [ProductController::class, 'index'])
        ->name('farmer.products.index');

    Route::get('/products/create', [ProductController::class, 'create'])
        ->name('farmer.products.create');

    Route::post('/products', [ProductController::class, 'store'])
        ->name('farmer.products.store');

    Route::get('/products/{product}/edit', [ProductController::class, 'edit'])
        ->name('farmer.products.edit');

    Route::put('/products/{product}', [ProductController::class, 'update'])
        ->name('farmer.products.update');

    Route::delete('/products/{product}', [ProductController::class, 'destroy'])
        ->name('farmer.products.destroy');
});

// Customer routes
Route::middleware(['auth', 'role:customer'])->prefix('customer')->group(function () {

    // Dashboard
    Route::get('/dashboard', [
        App\Http\Controllers\Customer\DashboardController::class,
        'index',
    ])->name('customer.dashboard');

    // Product discovery
    Route::get('/products', [CustomerProductController::class, 'index'])
        ->name('customer.products.index');

    Route::get('/products/{product}', [CustomerProductController::class, 'show'])
        ->name('customer.products.show');

    // Favorites
    Route::get('/favorites', [CustomerFavoriteController::class, 'index'])
        ->name('customer.favorites.index');

    Route::post('/favorites/products/{product}', [CustomerFavoriteController::class, 'storeProduct'])
        ->name('customer.favorites.products.store');

    Route::delete('/favorites/products/{product}', [CustomerFavoriteController::class, 'destroyProduct'])
        ->name('customer.favorites.products.destroy');

    Route::post('/favorites/farmers/{farmer}', [CustomerFavoriteController::class, 'storeFarmer'])
        ->name('customer.favorites.farmers.store');

    Route::delete('/favorites/farmers/{farmer}', [CustomerFavoriteController::class, 'destroyFarmer'])
        ->name('customer.favorites.farmers.destroy');

    // Farmer discovery
    Route::get('/farmers', [CustomerFarmerController::class, 'index'])
        ->name('customer.farmers.index');

    Route::get('/farmers/{farmer}', [CustomerFarmerController::class, 'show'])
        ->name('customer.farmers.show');

    // Market discovery
    Route::get('/markets', [CustomerMarketController::class, 'index'])
        ->name('customer.markets.index');

    Route::get('/markets/{market}', [CustomerMarketController::class, 'show'])
        ->name('customer.markets.show');

    // Preferred markets
    Route::post('/favorites/markets/{market}', [CustomerFavoriteController::class, 'storeMarket'])
        ->name('customer.favorites.markets.store');

    Route::delete('/favorites/markets/{market}', [CustomerFavoriteController::class, 'destroyMarket'])
        ->name('customer.favorites.markets.destroy');

    // Customer notifications
    Route::get('/notifications', [CustomerNotificationController::class, 'index'])
        ->name('customer.notifications.index');

    Route::patch('/notifications/{id}/read', [CustomerNotificationController::class, 'markAsRead'])
        ->name('customer.notifications.read');

    Route::patch('/notifications/read-all', [CustomerNotificationController::class, 'markAllAsRead'])
        ->name('customer.notifications.readAll');

    // Customer Orders
    Route::get('/orders', [App\Http\Controllers\Customer\OrderController::class, 'index'])->name('customer.orders.index');
    Route::get('/orders/create', [App\Http\Controllers\Customer\OrderController::class, 'create'])->name('customer.orders.create');
    Route::post('/orders', [App\Http\Controllers\Customer\OrderController::class, 'store'])->name('customer.orders.store');
    Route::get('/orders/{order}', [App\Http\Controllers\Customer\OrderController::class, 'show'])->name('customer.orders.show');
    Route::patch('/orders/{order}/cancel', [App\Http\Controllers\Customer\OrderController::class, 'cancel'])->name('customer.orders.cancel');

    // Customer Reviews
    Route::post('/reviews', [App\Http\Controllers\Customer\ReviewController::class, 'store'])->name('customer.reviews.store');
});

// Static pages
Route::view('/about', 'pages.about')->name('About');
Route::view('/contact', 'pages.contact')->name('Contact Us');

require __DIR__.'/auth.php';
