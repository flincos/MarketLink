<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Farmer\DashboardController;
use App\Http\Controllers\Farmer\ProfileController;
use App\Http\Controllers\Farmer\MarketController;
use App\Http\Controllers\Farmer\PickupSlotController;
use App\Http\Controllers\Farmer\OrderController;
use App\Http\Controllers\Farmer\ReviewController;
use App\Http\Controllers\Farmer\ProductController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/farmer/profile', [ProfileController::class, 'edit'])->name('farmer.profile');

Route::put('/farmer/profile', [ProfileController::class, 'update'])->name('farmer.profile.update');

Route::get('/farmer/dashboard', [DashboardController::class, 'index'])->name('farmer.dashboard');

Route::get('/farmer/markets', [MarketController::class, 'index'])->name('farmer.markets.index');

Route::get('/farmer/markets/create', [MarketController::class, 'create'])->name('farmer.markets.create');

Route::post('/farmer/markets', [MarketController::class, 'store'])->name('farmer.markets.store');

Route::delete('/farmer/markets/{market}', [MarketController::class, 'destroy'])->name('farmer.markets.destroy');

Route::get('/farmer/pickup-slots', [PickupSlotController::class, 'index'])->name('farmer.pickup-slots.index');

Route::get('/farmer/pickup-slots/create', [PickupSlotController::class, 'create'])->name('farmer.pickup-slots.create');

Route::post('/farmer/pickup-slots', [PickupSlotController::class, 'store'])->name('farmer.pickup-slots.store');

Route::get('/farmer/pickup-slots/{pickupSlot}/edit', [PickupSlotController::class, 'edit'])->name('farmer.pickup-slots.edit');

Route::put('/farmer/pickup-slots/{pickupSlot}', [PickupSlotController::class, 'update'])->name('farmer.pickup-slots.update');

Route::delete('/farmer/pickup-slots/{pickupSlot}', [PickupSlotController::class, 'destroy'])->name('farmer.pickup-slots.destroy');

Route::get('/farmer/orders', [OrderController::class, 'index'])->name('farmer.orders.index');

Route::get('/farmer/orders/{order}', [OrderController::class, 'show'])->name('farmer.orders.show');

Route::patch('/farmer/orders/{order}/status', [OrderController::class, 'updateStatus'])->name('farmer.orders.update-status');

Route::get('/farmer/orders/history', [OrderController::class, 'history'])->name('farmer.orders.history');

Route::get('/farmer/reviews', [ReviewController::class, 'index'])->name('farmer.reviews.index');

Route::patch('/farmer/reviews/{review}/respond', [ReviewController::class, 'respond'])->name('farmer.reviews.respond');

Route::get('/farmer/products', [ProductController::class, 'index'])
    ->name('farmer.products.index');

Route::get('/farmer/products/create', [ProductController::class, 'create'])
    ->name('farmer.products.create');

Route::post('/farmer/products', [ProductController::class, 'store'])->name('farmer.products.store');

Route::get('/farmer/products/{product}/edit', [ProductController::class, 'edit'])->name('farmer.products.edit');

Route::put('/farmer/products/{product}', [ProductController::class, 'update'])->name('farmer.products.update');

Route::delete('/farmer/products/{product}', [ProductController::class, 'destroy'])->name('farmer.products.destroy');

Route::view('/about', 'pages.about')->name('About');
Route::view('/contact','pages.contact')->name('Contact Us');

require __DIR__.'/auth.php';
