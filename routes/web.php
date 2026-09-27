<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ProfileController as UserProfileController;
use App\Http\Controllers\Farmer\DashboardController;
use App\Http\Controllers\Farmer\ProfileController;
use App\Http\Controllers\Farmer\MarketController;
use App\Http\Controllers\Farmer\PickupSlotController;
use App\Http\Controllers\Farmer\OrderController;
use App\Http\Controllers\Farmer\ReviewController;
use App\Http\Controllers\Farmer\ProductController;
use App\Http\Controllers\FarmerProfileController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\FarmerController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\ProductModerationController;
use App\Http\Controllers\Admin\ReviewModerationController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\MarketController as AdminMarketController;

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
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Farmers
    Route::get('/farmers', [FarmerController::class, 'index'])->name('farmers.index');
    Route::patch('/farmers/{farmerProfile}/approve', [FarmerController::class, 'approve'])->name('farmers.approve');
    Route::patch('/farmers/{farmerProfile}/suspend', [FarmerController::class, 'suspend'])->name('farmers.suspend');
    
    // Customers
    Route::get('/customers', [CustomerController::class, 'index'])->name('customers.index');
    Route::patch('/customers/{user}/activate', [CustomerController::class, 'activate'])->name('customers.activate');
    Route::patch('/customers/{user}/deactivate', [CustomerController::class, 'deactivate'])->name('customers.deactivate');

    // Product moderation
    Route::get('/products', [ProductModerationController::class, 'index'])->name('products.index');
    Route::patch('/products/{product}/hide', [ProductModerationController::class, 'hide'])->name('products.hide');
    Route::patch('/products/{product}/unhide', [ProductModerationController::class, 'unhide'])->name('products.unhide');

    // Review moderation
    Route::get('/reviews', [ReviewModerationController::class, 'index'])->name('reviews.index');
    Route::patch('/reviews/{review}/hide', [ReviewModerationController::class, 'hide'])->name('reviews.hide');
    Route::patch('/reviews/{review}/unhide', [ReviewModerationController::class, 'unhide'])->name('reviews.unhide');
    
    //category
    Route::resource('categories', CategoryController::class)->except(['show']);

    //Market
    Route::resource('markets', AdminMarketController::class)->except(['show']);
});

// Farmer routes
Route::middleware(['auth', 'role:farmer'])->prefix('farmer')->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('farmer.dashboard');

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

// Customer dashboard
Route::middleware(['auth', 'role:customer'])->prefix('customer')->group(function () {
    Route::get('/dashboard', fn () => view('customer.dashboard'))
        ->name('customer.dashboard');
});

// Static pages
Route::view('/about', 'pages.about')->name('About');
Route::view('/contact', 'pages.contact')->name('Contact Us');

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    // existing admin routes...

    Route::resource('markets', AdminMarketController::class)->except(['show']);

    Route::get('markets/map', [AdminMarketController::class, 'map'])->name('markets.map');
}); 

Route::middleware(['auth', 'role:customer'])
    ->prefix('customer')
    ->name('customer.')
    ->group(function () {
        // other customer routes...

        Route::get('notifications', function () {
            $notifications = auth()->user()
                ->notifications()
                ->orderByDesc('created_at')
                ->paginate(20);

            return view('customer.notifications.index', compact('notifications'));
        })->name('notifications.index');
    });

require __DIR__.'/auth.php';