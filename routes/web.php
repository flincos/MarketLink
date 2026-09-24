<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FarmerProfileController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return match (auth()->user()->role) {
        'admin' => redirect()->route('admin.dashboard'),
        'farmer' => redirect()->route('farmer.dashboard'),
        'customer' => redirect()->route('customer.dashboard'),
        default => redirect('/'),
    };
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'role:farmer'])->group(function () {
    Route::get('/farmer/profile/create', [FarmerProfileController::class, 'create'])
        ->name('farmer.profile.create');
    Route::post('/farmer/profile', [FarmerProfileController::class, 'store'])
        ->name('farmer.profile.store');
});

Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', fn () => view('admin.dashboard'))->name('admin.dashboard');
});

Route::middleware(['auth', 'role:farmer'])->prefix('farmer')->group(function () {
    Route::get('/dashboard', function () {
        $profile = auth()->user()->farmerProfile;

        if (! $profile) {
            return redirect()->route('farmer.profile.create');
        }

        return view('farmer.dashboard', ['profile' => $profile]);
    })->name('farmer.dashboard');
});

Route::middleware(['auth', 'role:customer'])->prefix('customer')->group(function () {
    Route::get('/dashboard', fn () => view('customer.dashboard'))->name('customer.dashboard');
});

Route::view('/about', 'pages.about')->name('About');
Route::view('/contact','pages.contact')->name('Contact Us');

require __DIR__.'/auth.php';
