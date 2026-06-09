<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SupplierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Kosongkan tabel suppliers
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('suppliers')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Isi data supplier sesuai kolom di migration
        DB::table('suppliers')->insert([
            [
                'nama_supplier' => 'PT Asus Indonesia',
                'alamat'        => 'Jl. Mangga Dua Raya, Jakarta',
                'no_telp'       => '081234567890',
                'created_at'    => date('Y-m-d H:i:s'),
                'updated_at'    => date('Y-m-d H:i:s'),
            ],
            [
                'nama_supplier' => 'CV Logitech Indo',
                'alamat'        => 'Harco Mas Mangga Dua, Jakarta',
                'no_telp'       => '089876543210',
                'created_at'    => date('Y-m-d H:i:s'),
                'updated_at'    => date('Y-m-d H:i:s'),
            ]
        ]);
    }
}
