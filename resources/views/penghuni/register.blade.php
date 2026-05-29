@extends('layouts/app')

@section('title', 'Daftar Penghuni Baru')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-100">
    <div class="bg-white p-8 rounded-2xl shadow-md w-full max-w-md">
        <h2 class="text-2xl font-bold text-center text-indigo-600 mb-2">KOSKITAA</h2>
        <p class="text-gray-600 text-center mb-6">Pendaftaran Akun Anak Kos Baru</p>
        
        <!-- Menampilkan Pesan Error Validasi -->
        @if ($errors->any())
            <div class="mb-4 p-3 bg-red-100 text-red-700 rounded-lg text-sm">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('penghuni.register.submit') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                <input type="text" name="nama_penghuni" value="{{ old('nama_penghuni') }}" required class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none">
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Email (Wajib @gmail.com)</label>
                <input type="email" name="email" value="{{ old('email') }}" required class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none">
            </div>
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                <input type="password" name="password" required class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none">
            </div>
            <button type="submit" class="w-full bg-indigo-600 text-white py-2 rounded-lg font-semibold hover:bg-indigo-700 transition">
                Daftar Sekarang
            </button>
            <p class="text-sm text-center text-gray-600 mt-4">
                Sudah punya akun? <a href="{{ route('penghuni.login') }}" class="text-indigo-600 hover:underline">Login disini</a>
            </p>
        </form>
    </div>
</div>
@endsection