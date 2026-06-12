<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    use HasFactory;

    // 1. Kasih tahu Laravel Primary Key yang baru
    protected $primaryKey = 'id_transaksi';

    // 2. Daftarkan semua kolom yang boleh diisi
    protected $fillable = [
        'id_barang',
        'id_user',
        'jenis_transaksi',
        'jumlah',
        'harga_beli',
        'harga_jual_aktual',
        'keterangan',
        'tanggal_transaksi'
    ];

    // 3. Relasi opsional ke tabel Barang & User
    public function barang()
    {
        return $this->belongsTo(Barang::class, 'id_barang', 'id_barang');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id');
    }
}
