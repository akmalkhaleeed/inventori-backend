<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
            'stok'        => 'nullable|integer',
            'harga'       => 'required|integer',
        ]);

        // 2. Simpan data ke database
        DB::table('barangs')->insert([
            'kategori_id' => $request->kategori_id,
            'supplier_id' => $request->supplier_id,
            'nama_barang' => $request->nama_barang,
            'stok'        => $request->stok ?? 0,
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

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Biarkan kosong
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // 1. Validasi data barang yang mau diubah
        $request->validate([
            'kategori_id' => 'required|integer|exists:kategoris,id',
            'supplier_id' => 'required|integer|exists:suppliers,id',
            'nama_barang' => 'required|string|max:255',
            'stok'        => 'nullable|integer',
            'harga'       => 'required|integer',
        ]);

        // 2. Cek apakah data barang ada di database
        $barang = DB::table('barangs')->where('id', $id)->first();

        if (!$barang) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Data barang tidak ditemukan'
            ], 404);
        }

        // 3. Eksekusi Update
        DB::table('barangs')->where('id', $id)->update([
            'kategori_id' => $request->kategori_id,
            'supplier_id' => $request->supplier_id,
            'nama_barang' => $request->nama_barang,
            'stok'        => $request->stok ?? 0,
            'harga'       => $request->harga,
            'updated_at'  => date('Y-m-d H:i:s'),
        ]);

        return response()->json([
            'status'  => 'success',
            'message' => 'Data barang berhasil diperbarui'
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // 1. Cek apakah data barang ada
        $barang = DB::table('barangs')->where('id', $id)->first();

        if (!$barang) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Data barang tidak ditemukan'
            ], 404);
        }

        // 2. Eksekusi Hapus
        DB::table('barangs')->where('id', $id)->delete();

        return response()->json([
            'status'  => 'success',
            'message' => 'Data barang berhasil dihapus'
        ], 200);
    }
}
