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
        Schema::create('transaksis', function (Blueprint $table) {
            // PK: id_transaksi (Auto Increment, Not Null)
            $table->id('id_transaksi');

            // FK: id_barang (Default: Null, terhubung ke id_barang di tabel barangs)
            $table->unsignedBigInteger('id_barang')->nullable();
            $table->foreign('id_barang')->references('id_barang')->on('barangs')->onDelete('set null');

            // FK: id_user (Default: Null, terhubung ke id di tabel users)
            // Catatan: Jika primary key di tabel users kamu adalah 'id' bawaan Laravel, maka pakai 'id'
            $table->unsignedBigInteger('id_user')->nullable();
            $table->foreign('id_user')->references('id')->on('users')->onDelete('set null');

            // Kolom Detail Transaksi
            $table->enum('jenis_transaksi', ['masuk', 'keluar']);
            $table->integer('jumlah');
            $table->double('harga_beli')->nullable();
            $table->double('harga_jual_aktual')->nullable();
            $table->text('keterangan')->nullable();

            // Tanggal Transaksi: datetime (Default: current_timestamp)
            $table->dateTime('tanggal_transaksi')->useCurrent();

            // Tetap pertahankan timestamps bawaan Laravel jika diperlukan (opsional)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksis');
    }
};
