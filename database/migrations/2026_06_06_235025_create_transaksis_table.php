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
            $table->id();
            // Tetap bawa user_id jika nanti mau dipakai untuk fitur login/auth tugas besarmu
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('barang_id')->constrained('barangs')->onDelete('cascade');
            $table->enum('jenis_transaksi', ['masuk', 'keluar']);
            $table->integer('jumlah');
            $table->date('tanggal_transaksi');
            $table->string('keterangan')->nullable(); // <-- Kita tambahkan ini agar tidak error lagi!
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
