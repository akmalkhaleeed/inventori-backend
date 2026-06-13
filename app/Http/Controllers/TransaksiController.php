<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransaksiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // PERBAIKAN: Ubah alias 'users.name as nama_petugas' menjadi 'users.name as name'
        // Agar sesuai dengan penangkap data di frontend React
        $transaksi = DB::table('transaksis')
            ->leftJoin('barangs', 'transaksis.id_barang', '=', 'barangs.id_barang')
            ->leftJoin('users', 'transaksis.id_user', '=', 'users.id')
            ->select('transaksis.*', 'barangs.nama_barang', 'users.name as name')
            ->orderBy('transaksis.tanggal_transaksi', 'desc') // Urutkan berdasarkan tanggal transaksi
            ->get();

        return response()->json([
            'status'  => 'success',
            'message' => 'Daftar riwayat transaksi berhasil diambil',
            'data'    => $transaksi
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // 1. Validasi Inputan sesuai nama kolom baru
        $request->validate([
            'id_user'           => 'required|integer|exists:users,id',
            'id_barang'         => 'required|integer|exists:barangs,id_barang',
            'jenis_transaksi'   => 'required|in:masuk,keluar',
            'jumlah'            => 'required|integer|min:1',
            'harga_beli'        => 'nullable|numeric',
            'harga_jual_aktual' => 'nullable|numeric',
            'tanggal_transaksi' => 'nullable|date',
            'keterangan'        => 'nullable|string',
        ]);

        // 2. Ambil data barang saat ini untuk dicek/diubah stoknya (Pakai id_barang)
        $barang = DB::table('barangs')->where('id_barang', $request->id_barang)->first();

        if (!$barang) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Data barang tidak ditemukan!'
            ], 404);
        }

        // Siapkan variabel untuk menampung stok baru
        $stokSekarang = $barang->stok;
        $stokBaru = $stokSekarang;

        // 3. Logika Pengkondisian Jenis Transaksi
        if ($request->jenis_transaksi == 'masuk') {
            $stokBaru = $stokSekarang + $request->jumlah;
        } else if ($request->jenis_transaksi == 'keluar') {
            if ($stokSekarang < $request->jumlah) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Stok tidak mencukupi! Stok saat ini hanya sisa ' . $stokSekarang
                ], 400);
            }
            $stokBaru = $stokSekarang - $request->jumlah;
        }

        // 4. Mulai Simpan ke Database
        // A. Insert data ke tabel transaksis
        DB::table('transaksis')->insert([
            'id_user'           => $request->id_user,
            'id_barang'         => $request->id_barang,
            'jenis_transaksi'   => $request->jenis_transaksi,
            'jumlah'            => $request->jumlah,
            'harga_beli'        => $request->harga_beli,
            'harga_jual_aktual' => $request->harga_jual_aktual,
            'keterangan'        => $request->keterangan,
            'tanggal_transaksi' => $request->tanggal_transaksi ?? date('Y-m-d H:i:s'),
            'created_at'        => date('Y-m-d H:i:s'),
            'updated_at'        => date('Y-m-d H:i:s'),
        ]);

        // B. Update stok baru ke tabel barangs (Pakai id_barang)
        DB::table('barangs')->where('id_barang', $request->id_barang)->update([
            'stok'       => $stokBaru,
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        return response()->json([
            'status'  => 'success',
            'message' => 'Transaksi barang ' . $request->jenis_transaksi . ' berhasil dicatat dan stok telah diperbarui.'
        ], 201); // 201 karena berhasil Create data
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Opsional kalau mau nampilin detail 1 transaksi
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Data transaksi tidak disarankan di-update untuk menjaga audit trail
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Data transaksi sebaiknya tidak dihapus (soft delete jika perlu)
    }
}
