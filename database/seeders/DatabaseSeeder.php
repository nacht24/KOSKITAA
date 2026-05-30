<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        Admin::create([
            'nama_admin' => 'Owner KosKitaa',
            'email_admin' => 'admin@gmail.com',
            'password_admin' => Hash::make('admin123'),
        ]);
    }
}