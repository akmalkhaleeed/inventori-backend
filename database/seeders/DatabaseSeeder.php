<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            KategoriSeeder::class, // Dipanggil duluan untuk mengisi tabel kategoris
            SupplierSeeder::class, // Dipanggil duluan untuk mengisi tabel suppliers
            BarangSeeder::class,   // Baru kemudian mengisi tabel barangs yang bergantung pada keduanya
        ]);
    }
}
