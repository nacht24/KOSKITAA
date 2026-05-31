<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Penghuni;
use App\Models\Kamar;
use App\Models\Tagihan;
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
        $penghuni = Penghuni::with(['kamar.kos', 'tagihan'])->findOrFail(Auth::id());

        $tagihanAktif = $penghuni->tagihan()
            ->whereIn('status_pembayaran', ['belum_bayar', 'menunggu_verifikasi'])
            ->latest()
            ->first();

        $riwayatTerakhir = $penghuni->tagihan()
            ->where('status_pembayaran', 'lunas')
            ->latest()
            ->take(5)
            ->get();

        return view('penghuni.dashboard', compact('penghuni', 'tagihanAktif', 'riwayatTerakhir'));
    }

    public function prosesRegister(Request $request)
    {
        $request->validate([
            'nama_penghuni' => 'required|string|max:100',
            'email'         => 'required|email|unique:penghuni,email|regex:/(.*)@gmail\.com$/i',
            'no_hp'         => 'required|numeric|digits_between:10,14',
            'password'      => 'required|string|min:5',
        ]);

        Penghuni::create([
            'nama_penghuni' => $request->nama_penghuni,
            'email'         => $request->email,
            'no_hp'         => $request->no_hp,
            'password'      => Hash::make($request->password),
        ]);

        return redirect()->route('login')->with('success', 'Akun berhasil dibuat.');
    }

    public function prosesLogin(Request $request)
    {
        $request->validate([
            'email' => 'required|email|regex:/(.*)@gmail\.com$/i',
            'password' => 'required|string',
        ]);

        $penghuni = Penghuni::where('email', $request->email)->first();

        if ($penghuni && Hash::check($request->password, $penghuni->password)) {
            Auth::login($penghuni);
            return redirect()->route('penghuni.dashboard');
        }

        return redirect()->back()->withErrors(['login_error' => 'Email atau Password salah.']);
    }

    public function indexAdmin()
    {
        $semuaPenghuni = Penghuni::with('kamar.kos')->get();
        $kamarTersedia = Kamar::where('status_kamar', 'kosong')->get();

        return view('admin.penghuni', compact('semuaPenghuni', 'kamarTersedia'));
    }

    public function assignKamar(Request $request, $id)
    {
        $request->validate(['id_kamar' => 'required|exists:kamar,id_kamar']);

        $penghuni = Penghuni::findOrFail($id);
        $kamar = Kamar::findOrFail($request->id_kamar);

        $penghuni->update(['id_kamar' => $kamar->id_kamar]);
        $kamar->update(['status_kamar' => 'terisi']);

        Tagihan::create([
            'id_penghuni'       => $penghuni->id_penghuni,
            'total_tagihan'     => $kamar->harga_kamar,
            'status_pembayaran' => 'belum_bayar',
            'tanggal_tagihan'   => now(),
            'bulan_tagihan'     => now()->translatedFormat('F'),
        ]);

        return redirect()->back()->with('success', 'Kamar berhasil di-plot.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_penghuni' => 'required|string|max:100',
            'email'         => 'required|email|regex:/(.*)@gmail\.com$/i|unique:penghuni,email,' . $id . ',id_penghuni',
            'no_hp'         => 'required|numeric|digits_between:10,14',
            'durasi_sewa'   => 'required|integer',
        ]);

        $penghuni = Penghuni::findOrFail($id);
        $kamarLamaId = $penghuni->id_kamar;
        $kamarBaruId = $request->id_kamar;

        $penghuni->update([
            'nama_penghuni' => $request->nama_penghuni,
            'email'         => $request->email,
            'no_hp'         => $request->no_hp,
            'durasi_sewa'   => $request->durasi_sewa,
            'id_kamar'      => $kamarBaruId ?: null,
        ]);

        if ($kamarLamaId != $kamarBaruId) {
            if ($kamarLamaId) Kamar::where('id_kamar', $kamarLamaId)->update(['status_kamar' => 'kosong']);
            if ($kamarBaruId) Kamar::where('id_kamar', $kamarBaruId)->update(['status_kamar' => 'terisi']);
        }

        return redirect()->back()->with('success', 'Data penyewa berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $penghuni = Penghuni::findOrFail($id);

        if ($penghuni->id_kamar) {
            Kamar::where('id_kamar', $penghuni->id_kamar)->update(['status_kamar' => 'kosong']);
        }

        $penghuni->delete();
        return redirect()->back()->with('success', 'Data penghuni berhasil dihapus.');
    }
}