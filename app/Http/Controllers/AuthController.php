<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // 1. Menampilkan satu halaman login utama untuk semua user
    public function showLogin()
    {
        return view('auth.login'); 
    }

    // 2. Memproses login satu pintu (Validasi silang Admin & Penghuni)
    public function login(Request $request)
    {
        // 1. Validasi input form utama
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // =========================================================================
        // JALUR 1: CEK TABEL PENGHUNI DULU (ANAK KOS)
        // =========================================================================
        $penghuniCredentials = [
            'email' => $request->email,
            'password' => $request->password,
        ];

        if (Auth::guard('web')->attempt($penghuniCredentials)) {
            $request->session()->regenerate();
            // Sukses -> langsung masuk panel penghuni
            return redirect()->route('penghuni.tagihan');
        }

        // =========================================================================
        // JALUR 2: KALAU BUKAN PENGHUNI, BARU CEK TABEL ADMIN (BAPAK KOS)
        // =========================================================================
        $adminCredentials = [
            'email_admin' => $request->email, 
            'password' => $request->password,
        ];

        if (Auth::guard('admin')->attempt($adminCredentials)) {
            $request->session()->regenerate();
            // Sukses -> masuk dashboard admin
            return redirect()->route('admin.dashboard');
        }

        // =========================================================================
        // JALUR 3: DUA-DUANYA KAGAK ADA
        // =========================================================================
        return redirect()->back()->withErrors([
            'login_error' => 'Email atau Password salah, cuy!',
        ])->withInput($request->only('email'));
    }

    // 3. Fungsi Logout Universal
    public function logout(Request $request)
    {
        if (Auth::guard('admin')->check()) {
            Auth::guard('admin')->logout();
        } elseif (Auth::guard('web')->check()) {
            Auth::guard('web')->logout();
        }

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}