<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // <-- Jangan lupa Wajib ada

class BarangController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Untuk data barang, kita pakai JOIN agar nama kategori & suppliernya ikut tampil (bukan cuma ID-nya)
        $barang = DB::table('barangs')
            ->join('kategoris', 'barangs.kategori_id', '=', 'kategoris.id')
            ->join('suppliers', 'barangs.supplier_id', '=', 'suppliers.id')
            ->select('barangs.*', 'kategoris.nama_kategori', 'suppliers.nama_supplier')
            ->get();

        return response()->json([
            'status'  => 'success',
            'message' => 'Daftar barang berhasil diambil',
            'data'    => $barang
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // 1. Validasi inputan (termasuk ngecek ID kategori & supplier ada atau nggak di database)
        $request->validate([
            'kategori_id' => 'required|integer|exists:kategoris,id',
            'supplier_id' => 'required|integer|exists:suppliers,id',
            'nama_barang' => 'required|string|max:255',
            'stok'        => 'nullable|integer', // Boleh kosong, karena di migration defaultnya 0
            'harga'       => 'required|integer',
        ]);

        // 2. Simpan data ke database
        DB::table('barangs')->insert([
            'kategori_id' => $request->kategori_id,
            'supplier_id' => $request->supplier_id,
            'nama_barang' => $request->nama_barang,
            'stok'        => $request->stok ?? 0, // Kalau stok nggak diisi, otomatis jadi 0
            'harga'       => $request->harga,
            'created_at'  => date('Y-m-d H:i:s'),
            'updated_at'  => date('Y-m-d H:i:s'),
        ]);

        // 3. Respons sukses
        return response()->json([
            'status'  => 'success',
            'message' => 'Data barang berhasil ditambahkan'
        ], 201);
    }

    // ... (Fungsi show, update, destroy biarkan kosong untuk sekarang)
}
