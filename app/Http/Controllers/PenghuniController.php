<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Penghuni;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class PenghuniController extends Controller
{
    public function showLogin()
    {
        return view('penghuni.login');
    }

    public function showRegister()
    {
        return view('penghuni.register');
    }

    public function dashboard()
    {
        return view('penghuni.dashboard');
    }

    // Poin 1: LOGIKA PROSES REGISTER
    public function prosesRegister(Request $request)
    {
        $request->validate([
            'nama_penghuni' => 'required|string|max:100',
            'email' => 'required|email|unique:penghuni,email|regex:/(.*)@gmail\.com$/i',
            'password' => 'required|string|min:5',
        ], [
            'email.regex' => 'Pendaftaran gagal! Anda wajib menggunakan akun email @gmail.com.',
            'email.unique' => 'Email ini sudah terdaftar di sistem KOSKITAA!'
        ]);

        Penghuni::create([
            'nama_penghuni' => $request->nama_penghuni,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('penghuni.login')->with('success', 'Akun berhasil dibuat! Silakan login.');
    }

    // Poin 2: LOGIKA PROSES LOGIN
    public function prosesLogin(Request $request)
    {
        // Validasi input form login awal
        $request->validate([
            'email' => 'required|email|regex:/(.*)@gmail\.com$/i',
            'password' => 'required|string',
        ], [
            'email.regex' => 'Akses ditolak! Login sistem hanya mengizinkan akun @gmail.com.'
        ]);

        $penghuni = Penghuni::where('email', $request->email)->first();

        if ($penghuni && Hash::check($request->password, $penghuni->password)) {
            Auth::login($penghuni);
            return redirect()->route('penghuni.dashboard');
        }

        // Jika salah, balikin dengan pesan error
        return redirect()->back()->withErrors(['login_error' => 'Email atau Password salah, silakan cek kembali!']);
    }
}