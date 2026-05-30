<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Penghuni;
use App\Models\Kamar; // Import model Kamar buat dropdown plotting
use App\Models\Tagihan; // Import model Tagihan buat otomatisasi data tagihan
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class PenghuniController extends Controller
{
    // ===================================================================
    // FITUR AUTENTIKASI PENYEWA (BAWAAN SAKLEK LU)
    // ===================================================================

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

    // LOGIKA PROSES REGISTER
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

    // LOGIKA PROSES LOGIN
    public function prosesLogin(Request $request)
    {
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

        return redirect()->back()->withErrors(['login_error' => 'Email atau Password salah, silakan cek kembali!']);
    }

    // ===================================================================
    // FITUR MANAJEMEN PENGHUNI SISI ADMIN (YANG BARU)
    // ===================================================================

    // 1. MENAMPILKAN DAFTAR PENGHUNI DI SISI ADMIN (DFD 2.3)
    public function indexAdmin()
    {
        // Tarik semua data anak kos beserta data kamar dan gedung kosannya (Eager Loading)
        $semuaPenghuni = Penghuni::with('kamar.kos')->get();

        // Ambil data unit kamar yang statusnya masih 'kosong' biar bisa dipilih di dropdown
        $kamarTersedia = Kamar::where('status_kamar', 'kosong')->get();

        return view('admin.penghuni', compact('semuaPenghuni', 'kamarTersedia'));
    }

    // 2. PROSES PLOT KAMAR KE PENGHUNI DAN OTOMATIS GENERATE TAGIHAN AKTIF
    public function assignKamar(Request $request, $id)
    {
        $request->validate([
            'id_kamar' => 'required|exists:kamar,id_kamar',
        ]);

        $penghuni = Penghuni::findOrFail($id);
        $kamar = Kamar::findOrFail($request->id_kamar);

        // Update kolom id_kamar milik si penghuni
        $penghuni->update([
            'id_kamar' => $kamar->id_kamar
        ]);

        // Ubah status kamar tersebut menjadi 'terisi' biar gak bentrok di dropdown lagi
        $kamar->update([
            'status_kamar' => 'terisi'
        ]);

        // AUTOMATIC GENERATE DATA TAGIHAN
        Tagihan::create([
            'id_penghuni'       => $penghuni->id_penghuni,
            'total_tagihan'     => $kamar->harga_kamar, // Otomatis ngikutin harga sewa kamar yang dipilih
            'status_pembayaran' => 'belum bayar',       // Default belum lunas biar ditagih ke anak kos
            'tanggal_tagihan'   => now(),
            'bulan_tagihan'     => now()->translatedFormat('F'), // <--- FIX: Otomatis ngisi "May", "June", dst sesuai bulan ril sekarang!
        ]);

        return redirect()->back()->with('success', 'Kamar sukses di-plot dan tagihan bulanan langsung aktif, cuy!');
    }

    // 2.5 PROSES UPDATE DATA PENYEWA VIA MODAL (TERINTEGRASI SISTEM PLOT KAMAR)
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_penghuni' => 'required|string|max:100',
            'email' => 'required|email|regex:/(.*)@gmail\.com$/i',
            'durasi_sewa'   => 'required|integer',
        ], [
            'email.regex' => 'Update gagal! Wajib menggunakan akun email @gmail.com.'
        ]);

        $penghuni = Penghuni::findOrFail($id);
        $kamarLamaId = $penghuni->id_kamar;
        $kamarBaruId = $request->id_kamar;

        // 1. Update data dasar si penghuni
        $penghuni->update([
            'nama_penghuni' => $request->nama_penghuni,
            'email'         => $request->email,
            'durasi_sewa'   => $request->durasi_sewa,
            'id_kamar'      => $kamarBaruId ?: null, // Kalau dilepas/kosong, di-set null
        ]);

        // 2. LOGIKA OTOMATIS STATUS KAMAR JIKA TERJADI PERPINDAHAN / PELEPASAN KAMAR
        if ($kamarLamaId != $kamarBaruId) {
            // Jika sebelumnya dia punya kamar lama, kembalikan status kamar lama jadi 'kosong'
            if ($kamarLamaId) {
                Kamar::where('id_kamar', $kamarLamaId)->update(['status_kamar' => 'kosong']);
            }

            // Jika dia dipindahkan ke kamar baru, ubah status kamar baru jadi 'terisi'
            if ($kamarBaruId) {
                Kamar::where('id_kamar', $kamarBaruId)->update(['status_kamar' => 'terisi']);
            }
        }

        return redirect()->back()->with('success', 'Data penyewa berhasil diperbarui dengan aman!');
    }
    
    // 3. PROSES KELUARKAN / HAPUS PENGHUNI KOS
    public function destroy($id)
    {
        $penghuni = Penghuni::findOrFail($id);

        // Jika dia punya kamar, kosongkan kembali status kamarnya sebelum dihapus
        if ($penghuni->id_kamar) {
            Kamar::where('id_kamar', $penghuni->id_kamar)->update([
                'status_kamar' => 'kosong'
            ]);
        }

        $penghuni->delete();
        return redirect()->back()->with('success', 'Data penghuni berhasil dihapus dari sistem!');
    }
}