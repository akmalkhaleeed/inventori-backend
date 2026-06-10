<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // <-- Wajib di-import untuk Query Builder

class TransaksiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Mengambil semua riwayat transaksi digabung dengan nama barang dan nama user pencatatnya
        $transaksi = DB::table('transaksis')
            ->join('barangs', 'transaksis.barang_id', '=', 'barangs.id')
            ->join('users', 'transaksis.user_id', '=', 'users.id') // Kita gabungkan juga dengan tabel users
            ->select('transaksis.*', 'barangs.nama_barang', 'users.name as nama_petugas')
            ->orderBy('transaksis.created_at', 'desc')
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
        // 1. Validasi Inputan
        $request->validate([
            'user_id'           => 'required|integer|exists:users,id', // <-- Validasi user_id wajib ada
            'barang_id'         => 'required|integer|exists:barangs,id',
            'jenis_transaksi'   => 'required|in:masuk,keluar', // Hanya boleh diisi 'masuk' atau 'keluar'
            'jumlah'            => 'required|integer|min:1',   // Minimal input angka 1
            'tanggal_transaksi' => 'required|date',
            'keterangan'        => 'nullable|string',
        ]);

        // 2. Ambil data barang saat ini untuk dicek/diubah stoknya
        $barang = DB::table('barangs')->where('id', $request->barang_id)->first();

        // Siapkan variabel untuk menampung stok baru
        $stokSekarang = $barang->stok;
        $stokBaru = $stokSekarang;

        // 3. Logika Pengkondisian Jenis Transaksi
        if ($request->jenis_transaksi == 'masuk') {
            // Kalau barang masuk, stok otomatis bertambah
            $stokBaru = $stokSekarang + $request->jumlah;
        } else if ($request->jenis_transaksi == 'keluar') {
            // Kalau barang keluar, cek dulu apakah stoknya cukup atau tidak
            if ($stokSekarang < $request->jumlah) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Stok tidak mencukupi! Stok saat ini hanya sisa ' . $stokSekarang
                ], 400); // 400 Bad Request
            }
            // Kalau stok cukup, otomatis berkurang
            $stokBaru = $stokSekarang - $request->jumlah;
        }

        // 4. Mulai Simpan ke Database
        // A. Insert data ke tabel transaksis
        DB::table('transaksis')->insert([
            'user_id'           => $request->user_id, // <-- Sekarang user_id dijamin masuk ke database!
            'barang_id'         => $request->barang_id,
            'jenis_transaksi'   => $request->jenis_transaksi,
            'jumlah'            => $request->jumlah,
            'tanggal_transaksi' => $request->tanggal_transaksi,
            'keterangan'        => $request->keterangan,
            'created_at'        => date('Y-m-d H:i:s'),
            'updated_at'        => date('Y-m-d H:i:s'),
        ]);

        // B. Update stok baru ke tabel barangs
        DB::table('barangs')->where('id', $request->barang_id)->update([
            'stok'       => $stokBaru,
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        return response()->json([
            'status'  => 'success',
            'message' => 'Transaksi barang ' . $request->jenis_transaksi . ' berhasil dicatat dan stok telah diperbarui.'
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Biasanya data transaksi tidak disarankan untuk di-update langsung demi keaslian data (audit trail)
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
