<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    use HasFactory;

    // 1. KASIH TAHU LARAVEL KALAU PRIMARY KEY-NYA BUKAN 'id'
    protected $primaryKey = 'id_barang';

    // 2. DAFTARKAN SEMUA KOLOM BARU AGAR BISA DIISI LEWAT API/CONTROLLER
    protected $fillable = [
        'id_kategori',
        'id_supplier',
        'nama_barang',
        'stok',
        'satuan',
        'harga_beli',
        'harga_jual',
        'lokasi_rak',
        'stok_minimum',
        'deskripsi'
    ];

    // 3. RELASI KE TABEL KATEGORI (Opsional, tapi berguna banget nanti kalau mau pakai Eloquent)
    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'id_kategori', 'id');
    }

    // 4. RELASI KE TABEL SUPPLIER (Opsional)
    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'id_supplier', 'id');
    }
}
