<?php

use App\Http\Controllers\BookingController;
use App\Http\Controllers\CancelController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EstimasiController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ServisController;
use App\Http\Controllers\StatusController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\EstimasiController as AdminEstimasiController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\CabangController as AdminCabangController;
use App\Http\Controllers\Admin\PenugasanController as AdminPenugasanController;
use App\Http\Controllers\Admin\SlaController as AdminSlaController;
use App\Http\Controllers\Admin\AuditController as AdminAuditController;
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

        // Pembatalan booking (hanya pelanggan, hanya saat status Diterima)
        Route::post('/servis/{servis}/cancel', [CancelController::class, 'cancel'])->name('servis.cancel');
    });

    // Servis detail (any authenticated user)
    Route::get('/servis/{servis}', [ServisController::class, 'show'])->name('servis.show');

    // Invoice servis (any authenticated user — ownership check in controller)
    Route::get('/servis/{servis}/invoice', [ServisController::class, 'invoice'])->name('servis.invoice');

    // Teknisi only
    Route::middleware('role:teknisi')->group(function () {
        // Update status (combined: status + catatan + parts + harga + foto) — exactly 1 step forward
        Route::post('/servis/{servis}/status', [StatusController::class, 'update'])->name('servis.status');
    });

    // Invoice item management (teknisi + admin)
    Route::middleware('role:teknisi,admin')->group(function () {
        Route::get('/servis/{servis}/invoice/edit', [InvoiceController::class, 'edit'])->name('invoice.edit');
        Route::post('/servis/{servis}/invoice/items', [InvoiceController::class, 'addItem'])->name('invoice.addItem');
        Route::delete('/invoice/items/{item}', [InvoiceController::class, 'deleteItem'])->name('invoice.deleteItem');
    });

    // ─── Admin Panel ──────────────────────────────────────────────
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        // Dashboard
        Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');

        // Estimasi Harga CRUD
        Route::get('/estimasi', [AdminEstimasiController::class, 'index'])->name('estimasi.index');
        Route::post('/estimasi', [AdminEstimasiController::class, 'store'])->name('estimasi.store');
        Route::put('/estimasi/{estimasi}', [AdminEstimasiController::class, 'update'])->name('estimasi.update');
        Route::delete('/estimasi/{estimasi}', [AdminEstimasiController::class, 'destroy'])->name('estimasi.destroy');

        // User Management
        Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
        Route::post('/users', [AdminUserController::class, 'store'])->name('users.store');
        Route::put('/users/{user}', [AdminUserController::class, 'update'])->name('users.update');
        Route::post('/users/{user}/toggle', [AdminUserController::class, 'toggleActive'])->name('users.toggle');
        Route::post('/users/{user}/reset-password', [AdminUserController::class, 'resetPassword'])->name('users.resetPassword');

        // Cabang CRUD
        Route::get('/cabang', [AdminCabangController::class, 'index'])->name('cabang.index');
        Route::post('/cabang', [AdminCabangController::class, 'store'])->name('cabang.store');
        Route::put('/cabang/{cabang}', [AdminCabangController::class, 'update'])->name('cabang.update');
        Route::delete('/cabang/{cabang}', [AdminCabangController::class, 'destroy'])->name('cabang.destroy');

        // Penugasan Teknisi
        Route::get('/penugasan', [AdminPenugasanController::class, 'index'])->name('penugasan.index');
        Route::post('/penugasan/{servis}/assign', [AdminPenugasanController::class, 'assign'])->name('penugasan.assign');
        Route::post('/penugasan/{servis}/auto', [AdminPenugasanController::class, 'autoAssign'])->name('penugasan.auto');
        Route::post('/penugasan/auto-all', [AdminPenugasanController::class, 'autoAssignAll'])->name('penugasan.autoAll');

        // SLA Config
        Route::get('/sla', [AdminSlaController::class, 'index'])->name('sla.index');
        Route::post('/sla', [AdminSlaController::class, 'store'])->name('sla.store');
        Route::put('/sla/{sla}', [AdminSlaController::class, 'update'])->name('sla.update');
        Route::delete('/sla/{sla}', [AdminSlaController::class, 'destroy'])->name('sla.destroy');

        // Audit Log
        Route::get('/audit', [AdminAuditController::class, 'index'])->name('audit.index');
    });
});

require __DIR__.'/auth.php';
