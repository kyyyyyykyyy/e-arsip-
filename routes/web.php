<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DocumentController; 
use App\Http\Controllers\UserController;
use App\Http\Controllers\ActivityLogController; 
use App\Http\Controllers\SettingController; // <-- Wajib import controller Setting
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => false, // <-- TOMBOL REGISTER DIMATIKAN
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

// ====================================================
// ROUTING UNTUK SISTEM E-ARSIP
// ====================================================
Route::middleware(['auth', 'verified'])->group(function () {
    
    // 1. Halaman Dashboard Utama (Grafik & Statistik)
    Route::get('/dashboard', [DocumentController::class, 'dashboard'])->name('dashboard');
    
    // 2. Halaman Daftar Arsip (Tabel, Search, Filter)
    Route::get('/arsip', [DocumentController::class, 'index'])->name('arsip.index');
    
    // 3. Proses CRUD Arsip
    Route::post('/documents', [DocumentController::class, 'store'])->name('documents.store'); // Upload Baru
    Route::delete('/documents/{document}', [DocumentController::class, 'destroy'])->name('documents.destroy'); // Hapus
    Route::post('/documents/{document}/update', [DocumentController::class, 'update'])->name('documents.update'); // Update/Edit
    
    // 4. Proses Download File Fisik
    Route::get('/documents/{document}/download', [DocumentController::class, 'download'])->name('documents.download');

    // 5. Halaman Laporan & Fitur Export Excel
    Route::get('/laporan', [DocumentController::class, 'laporan'])->name('laporan.index');
    Route::get('/laporan/export', [DocumentController::class, 'export'])->name('laporan.export');

    // ====================================================
    // 6. RUTE KELOLA USER (MANAJEMEN AKUN PEGAWAI)
    // ====================================================
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');

    // ====================================================
    // 7. RUTE LOG AKTIVITAS (CCTV)
    // ====================================================
    Route::get('/log-aktivitas', [ActivityLogController::class, 'index'])->name('logs.index');

    // ====================================================
    // 8. RUTE PENGATURAN SISTEM
    // ====================================================
    Route::get('/pengaturan', [SettingController::class, 'index'])->name('settings.index');
    Route::post('/pengaturan', [SettingController::class, 'update'])->name('settings.update');

});
// ====================================================

// Rute Profil Bawaan Laravel Breeze
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';