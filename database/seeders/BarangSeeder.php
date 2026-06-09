<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BarangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Kosongkan tabel barangs terlebih dahulu sebelum di-seed ulang
        // Menggunakan pernyataan mentah untuk menghindari kendala kunci asing saat mengosongkan tabel
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('barangs')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // 2. Ambil ID sampel dari tabel kategoris dan suppliers yang sudah di-seed sebelumnya
        // Jika belum ada seeder kategori/supplier, kita buat asumsi ID 1 dan 2 tersedia
        $kategoriId1 = DB::table('kategoris')->value('id') ?? 1;
        $kategoriId2 = DB::table('kategoris')->skip(1)->value('id') ?? 2;

        $supplierId1 = DB::table('suppliers')->value('id') ?? 1;
        $supplierId2 = DB::table('suppliers')->skip(1)->value('id') ?? 2;

        // 3. Masukkan data barang tiruan sesuai dengan kolom migration kamu
        DB::table('barangs')->insert([
            [
                'kategori_id' => $kategoriId1,
                'supplier_id' => $supplierId1,
                'nama_barang' => 'Laptop ASUS Vivobook',
                'stok'        => 10,
                'harga'       => 8500000,
                'created_at'  => date('Y-m-d H:i:s'),
                'updated_at'  => date('Y-m-d H:i:s'),
            ],
            [
                'kategori_id' => $kategoriId2,
                'supplier_id' => $supplierId2,
                'nama_barang' => 'Mouse Logitech Wireless',
                'stok'        => 50,
                'harga'       => 300000,
                'created_at'  => date('Y-m-d H:i:s'),
                'updated_at'  => date('Y-m-d H:i:s'),
            ]
        ]);
    }
}
