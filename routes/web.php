<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PenghuniController;
use App\Http\Controllers\KamarController;
use App\Http\Controllers\TagihanController;

// 1. Jalur Halaman Utama / Landing Depan
Route::get('/', function () {
    return view('welcome');
});

// 2. JALUR GROUP: PENGHUNI / ANAK KOS
Route::prefix('penghuni')->group(function () {
    Route::get('/login', [PenghuniController::class, 'showLogin'])->name('penghuni.login');
    Route::get('/register', [PenghuniController::class, 'showRegister'])->name('penghuni.register');
    Route::get('/dashboard', [PenghuniController::class, 'dashboard'])->name('penghuni.dashboard');
    Route::get('/tagihan', [TagihanController::class, 'index'])->name('penghuni.tagihan');
    Route::post('/register', [PenghuniController::class, 'prosesRegister'])->name('penghuni.register.submit');
Route::post('/login', [PenghuniController::class, 'prosesLogin'])->name('penghuni.login.submit');
});

// 3. JALUR GROUP: ADMIN / PEMILIK KOS
Route::prefix('admin')->group(function () {
    Route::get('/login', [AdminController::class, 'showLogin'])->name('admin.login');
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/kamar', [KamarController::class, 'index'])->name('admin.kamar');
    Route::get('/penghuni', [PenghuniController::class, 'index'])->name('admin.penghuni');
});