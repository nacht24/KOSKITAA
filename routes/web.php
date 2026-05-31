<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PenghuniController;
use App\Http\Controllers\KamarController;
use App\Http\Controllers\TagihanController;
use App\Http\Controllers\KosController;
use App\Http\Controllers\PembayaranController;
use App\Http\Controllers\PengeluaranController;
use App\Http\Controllers\LaporanController;

// 1. Jalur Halaman Utama & Autentikasi Publik
Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// HALAMAN DAFTAR PENGHUNI
Route::get('/penghuni/register', [PenghuniController::class, 'showRegister'])->name('penghuni.register');
Route::post('/penghuni/register', [PenghuniController::class, 'prosesRegister'])->name('penghuni.register.submit');

// 2. JALUR GROUP: PENGHUNI / ANAK KOS (KHUSUS YANG SUDAH LOGIN)
Route::prefix('penghuni')->middleware('auth:web')->group(function () {
Route::get('/dashboard', [PenghuniController::class, 'dashboard'])->name('penghuni.dashboard');
Route::get('/upload/{id}', [TagihanController::class, 'showUpload'])->name('penghuni.upload');
Route::get('/tagihan', [TagihanController::class, 'index'])->name('penghuni.tagihan');
Route::post('/tagihan/bayar', [TagihanController::class, 'bayarTagihan'])->name('penghuni.tagihan.submit');
Route::get('/riwayat', [TagihanController::class, 'riwayat'])->name('penghuni.riwayat');
});

// 3. JALUR GROUP: ADMIN / PEMILIK KOS (KHUSUS ADMIN YANG SUDAH LOGIN)
Route::prefix('admin')->middleware('auth:admin')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    // MASTER DATA KOS
    Route::get('/kos', [KosController::class, 'index'])->name('admin.kos');
    Route::post('/kos', [KosController::class, 'store'])->name('admin.kos.store');
    Route::delete('/kos/{id}', [KosController::class, 'destroy'])->name('admin.kos.destroy');
    // MASTER DATA KAMAR
    Route::resource('kamar', KamarController::class)->names([
        'index' => 'admin.kamar',
        'store' => 'admin.kamar.store',
        'update' => 'admin.kamar.update',
        'destroy' => 'admin.kamar.destroy',
    ]);
    // MASTER DATA PENGHUNI
    Route::get('/penghuni', [PenghuniController::class, 'indexAdmin'])->name('admin.penghuni');
    Route::put('/penghuni/assign/{id}', [PenghuniController::class, 'assignKamar'])->name('admin.penghuni.assign');
    Route::put('/penghuni/{id}', [PenghuniController::class, 'update'])->name('admin.penghuni.update'); // Ditambah garing (/) di depan biar konsisten
    Route::delete('/penghuni/{id}', [PenghuniController::class, 'destroy'])->name('admin.penghuni.destroy');
    // MASTER DATA VERIFIKASI PEMBAYARAN
    Route::get('/pembayaran', [PembayaranController::class, 'indexAdmin'])->name('admin.pembayaran');
    Route::put('/pembayaran/setujui/{id}', [PembayaranController::class, 'setujuiPembayaran'])->name('admin.pembayaran.setujui');
    Route::put('/pembayaran/tolak/{id}', [PembayaranController::class, 'tolakPembayaran'])->name('admin.pembayaran.tolak');
    // MASTER DATA PENGELUARAN
    Route::resource('pengeluaran', PengeluaranController::class)->names([
        'index' => 'admin.pengeluaran.index',
        'store' => 'admin.pengeluaran.store',
        'update' => 'admin.pengeluaran.update',
        'destroy' => 'admin.pengeluaran.destroy',
    ]);
    Route::get('/laporan', [LaporanController::class, 'index'])->name('admin.laporan');
});