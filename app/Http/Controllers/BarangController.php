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
        // PERBAIKAN: Ubah kategoris.id menjadi kategoris.id_kategori & suppliers.id menjadi suppliers.id_supplier
        $barang = DB::table('barangs')
            ->leftJoin('kategoris', 'barangs.id_kategori', '=', 'kategoris.id_kategori')
            ->leftJoin('suppliers', 'barangs.id_supplier', '=', 'suppliers.id_supplier')
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
        // PERBAIKAN: Validasi exists diubah ke id_kategori dan id_supplier
        $request->validate([
            'id_kategori'  => 'nullable|integer|exists:kategoris,id_kategori',
            'id_supplier'  => 'nullable|integer|exists:suppliers,id_supplier',
            'nama_barang'  => 'required|string|max:100',
            'stok'         => 'nullable|integer',
            'satuan'       => 'nullable|string|max:20',
            'harga_beli'   => 'nullable|numeric',
            'harga_jual'   => 'required|numeric',
            'lokasi_rak'   => 'nullable|string|max:100',
            'stok_minimum' => 'nullable|integer',
            'deskripsi'    => 'nullable|string',
        ]);

        $stokAwal = $request->stok ?? 0;
        $now = date('Y-m-d H:i:s');

        // 2. Simpan data ke database
        // PERBAIKAN: pakai insertGetId supaya kita tahu id_barang yang baru dibuat,
        // dibutuhkan untuk membuat transaksi stok awal di bawah.
        $idBarangBaru = DB::table('barangs')->insertGetId([
            'id_kategori'  => $request->id_kategori,
            'id_supplier'  => $request->id_supplier,
            'nama_barang'  => $request->nama_barang,
            'stok'         => $stokAwal,
            'satuan'       => $request->satuan,
            'harga_beli'   => $request->harga_beli,
            'harga_jual'   => $request->harga_jual ?? 0.00,
            'lokasi_rak'   => $request->lokasi_rak,
            'stok_minimum' => $request->stok_minimum ?? 5,
            'deskripsi'    => $request->deskripsi,
            'created_at'   => $now,
            'updated_at'   => $now,
        ], 'id_barang');

        // PERBAIKAN: Auto-catat stok awal sebagai transaksi "masuk" supaya muncul
        // juga di Laporan Transaksi / Barang Masuk, bukan cuma di tabel barangs.
        if ($stokAwal > 0) {
            DB::table('transaksis')->insert([
                'id_user'           => auth()->id(),
                'id_barang'         => $idBarangBaru,
                'jenis_transaksi'   => 'masuk',
                'jumlah'            => $stokAwal,
                'harga_beli'        => $request->harga_beli,
                'harga_jual_aktual' => null,
                'keterangan'        => 'Stok awal saat barang baru ditambahkan',
                'tanggal_transaksi' => $now,
                'created_at'        => $now,
                'updated_at'        => $now,
            ]);
        }

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
        // PERBAIKAN: Sesuaikan ON join dengan nama kolom PK yang benar
        $barang = DB::table('barangs')
            ->leftJoin('kategoris', 'barangs.id_kategori', '=', 'kategoris.id_kategori')
            ->leftJoin('suppliers', 'barangs.id_supplier', '=', 'suppliers.id_supplier')
            ->select('barangs.*', 'kategoris.nama_kategori', 'suppliers.nama_supplier')
            ->where('barangs.id_barang', $id)
            ->first();

        if (!$barang) {
            return response()->json(['status' => 'error', 'message' => 'Data tidak ditemukan'], 404);
        }

        return response()->json(['status' => 'success', 'data' => $barang], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // PERBAIKAN: Validasi exists diubah ke id_kategori dan id_supplier
        $request->validate([
            'id_kategori'  => 'nullable|integer|exists:kategoris,id_kategori',
            'id_supplier'  => 'nullable|integer|exists:suppliers,id_supplier',
            'nama_barang'  => 'required|string|max:100',
            'stok'         => 'nullable|integer',
            'satuan'       => 'nullable|string|max:20',
            'harga_beli'   => 'nullable|numeric',
            'harga_jual'   => 'required|numeric',
            'lokasi_rak'   => 'nullable|string|max:100',
            'stok_minimum' => 'nullable|integer',
            'deskripsi'    => 'nullable|string',
        ]);

        // 2. Cek apakah data barang ada di database (Pakai id_barang)
        $barang = DB::table('barangs')->where('id_barang', $id)->first();

        if (!$barang) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Data barang tidak ditemukan'
            ], 404);
        }

        // 3. Eksekusi Update
        DB::table('barangs')->where('id_barang', $id)->update([
            'id_kategori'  => $request->id_kategori,
            'id_supplier'  => $request->id_supplier,
            'nama_barang'  => $request->nama_barang,
            'stok'         => $request->stok ?? 0,
            'satuan'       => $request->satuan,
            'harga_beli'   => $request->harga_beli,
            'harga_jual'   => $request->harga_jual ?? 0.00,
            'lokasi_rak'   => $request->lokasi_rak,
            'stok_minimum' => $request->stok_minimum ?? 5,
            'deskripsi'    => $request->deskripsi,
            'updated_at'   => date('Y-m-d H:i:s'),
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
        // 1. Cek apakah data barang ada (Pakai id_barang)
        $barang = DB::table('barangs')->where('id_barang', $id)->first();

        if (!$barang) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Data barang tidak ditemukan'
            ], 404);
        }

        // 2. Eksekusi Hapus
        DB::table('barangs')->where('id_barang', $id)->delete();

        return response()->json([
            'status'  => 'success',
            'message' => 'Data barang berhasil dihapus'
        ], 200);
    }
}
