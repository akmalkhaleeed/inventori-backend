<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    use HasFactory;

    // Kasih tahu Laravel Primary Key yang baru
    protected $primaryKey = 'id_kategori';

    // Daftarkan kolom yang boleh diisi lewat form/API
    protected $fillable = [
        'nama_kategori',
    ];
}
