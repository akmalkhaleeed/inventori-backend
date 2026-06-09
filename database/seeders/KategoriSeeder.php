<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KategoriSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Kosongkan tabel kategoris
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('kategoris')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Isi data kategori sesuai kolom 'nama_kategori'
        DB::table('kategoris')->insert([
            [
                'nama_kategori' => 'Elektronik',
                'created_at'    => date('Y-m-d H:i:s'),
                'updated_at'    => date('Y-m-d H:i:s'),
            ],
            [
                'nama_kategori' => 'Aksesoris Komputer',
                'created_at'    => date('Y-m-d H:i:s'),
                'updated_at'    => date('Y-m-d H:i:s'),
            ]
        ]);
    }
}
