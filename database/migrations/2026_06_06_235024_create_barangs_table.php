<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('barangs', function (Blueprint $table) {
            // PK: id_barang (Otomatis Auto Increment & Not Null)
            $table->id('id_barang');

            // FK: id_kategori (Default Null, Set Null jika kategori dihapus)
            $table->unsignedBigInteger('id_kategori')->nullable();
            // PERBAIKAN: Ubah references('id') menjadi references('id_kategori')
            $table->foreign('id_kategori')->references('id_kategori')->on('kategoris')->onDelete('set null');

            // FK: id_supplier (Default Null, Set Null jika supplier dihapus)
            $table->unsignedBigInteger('id_supplier')->nullable();
            // PERBAIKAN: Ubah references('id') menjadi references('id_supplier')
            $table->foreign('id_supplier')->references('id_supplier')->on('suppliers')->onDelete('set null');

            // Kolom Data Barang sesuai spesifikasi
            $table->string('nama_barang', 100);
            $table->integer('stok')->default(0);
            $table->string('satuan', 20)->nullable();
            $table->decimal('harga_beli', 12, 2)->nullable();
            $table->decimal('harga_jual', 15, 2)->default(0.00);
            $table->string('lokasi_rak', 100)->nullable();
            $table->integer('stok_minimum')->default(5);
            $table->text('deskripsi')->nullable();

            // Timestamps (Otomatis membuat created_at dan updated_at)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('barangs');
    }
};
