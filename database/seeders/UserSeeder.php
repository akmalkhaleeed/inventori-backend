<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 🔒 Membuat akun Admin utama (Bisa mengelola user & master data)
        User::create([
            'name' => 'Akmal Admin',
            'username' => 'admin',
            'email' => 'admin@inventori.com',
            'password' => Hash::make('admin123'), // Otomatis di-hash rahasia aman!
            'role' => 'admin',
        ]);

        // 🔒 Membuat akun Petugas Gudang (Mencatat transaksi masuk/keluar)
        User::create([
            'name' => 'Diaz Petugas',
            'username' => 'petugas',
            'email' => 'petugas@inventori.com',
            'password' => Hash::make('petugas123'),
            'role' => 'petugas',
        ]);

        // 🔒 Membuat akun Pimpinan (Melihat laporan sirkulasi barang)
        User::create([
            'name' => 'Pak Pimpinan',
            'username' => 'pimpinan',
            'email' => 'pimpinan@inventori.com',
            'password' => Hash::make('pimpinan123'),
            'role' => 'pimpinan',
        ]);
    }
}
