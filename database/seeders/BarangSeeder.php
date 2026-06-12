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

        // 3. Masukkan data barang tiruan sesuai dengan kolom migration terbaru
        DB::table('barangs')->insert([
            [
                'id_kategori'  => $kategoriId1,
                'id_supplier'  => $supplierId1,
                'nama_barang'  => 'Laptop ASUS Vivobook',
                'stok'         => 10,
                'satuan'       => 'Unit',
                'harga_beli'   => 7500000,
                'harga_jual'   => 8500000,
                'lokasi_rak'   => 'A-01',
                'stok_minimum' => 5,
                'deskripsi'    => 'Laptop kantoran standar dengan performa stabil.',
                'created_at'   => date('Y-m-d H:i:s'),
                'updated_at'   => date('Y-m-d H:i:s'),
            ],
            [
                'id_kategori'  => $kategoriId2,
                'id_supplier'  => $supplierId2,
                'nama_barang'  => 'Mouse Logitech Wireless',
                'stok'         => 50,
                'satuan'       => 'Pcs',
                'harga_beli'   => 150000,
                'harga_jual'   => 300000,
                'lokasi_rak'   => 'B-05',
                'stok_minimum' => 10,
                'deskripsi'    => 'Mouse nirkabel hemat baterai.',
                'created_at'   => date('Y-m-d H:i:s'),
                'updated_at'   => date('Y-m-d H:i:s'),
            ]
        ]);
    }
}
