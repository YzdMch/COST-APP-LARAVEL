<?php

use App\Http\Controllers\BookingController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EstimasiController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ServisController;
use App\Http\Controllers\StatusController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::get('/', [PageController::class, 'welcome'])->name('home');
Route::get('/estimasi', [EstimasiController::class, 'index'])->name('estimasi');
Route::post('/estimasi/hitung', [EstimasiController::class, 'hitung'])->name('estimasi.hitung');

// Auth required
Route::middleware('auth')->group(function () {
    // Dashboard (auto-detect role)
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profile (from Breeze)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Booking (pelanggan only)
    Route::middleware('role:pelanggan')->group(function () {
        Route::get('/booking', [BookingController::class, 'create'])->name('booking.create');
        Route::post('/booking', [BookingController::class, 'store'])->name('booking.store');
    });

    // Servis detail (any authenticated user)
    Route::get('/servis/{servis}', [ServisController::class, 'show'])->name('servis.show');

    // Teknisi only
    Route::middleware('role:teknisi')->group(function () {
        Route::post('/servis/{servis}/status', [StatusController::class, 'update'])->name('servis.status');
        Route::post('/servis/{servis}/harga', [ServisController::class, 'updateHarga'])->name('servis.harga');
    });
});

require __DIR__.'/auth.php';
