<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KategoriController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $kategori = DB::table('kategoris')->get();

        return response()->json([
            'status'  => 'success',
            'message' => 'Daftar kategori berhasil diambil',
            'data'    => $kategori
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:255',
        ]);

        DB::table('kategoris')->insert([
            'nama_kategori' => $request->nama_kategori,
            'created_at'    => date('Y-m-d H:i:s'),
            'updated_at'    => date('Y-m-d H:i:s'),
        ]);

        return response()->json([
            'status'  => 'success',
            'message' => 'Kategori berhasil ditambahkan'
        ], 201);
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
        $request->validate([
            'nama_kategori' => 'required|string|max:255',
        ]);

        // Cek pakai id_kategori
        $kategori = DB::table('kategoris')->where('id_kategori', $id)->first();

        if (!$kategori) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Data kategori tidak ditemukan'
            ], 404);
        }

        // Update pakai id_kategori
        DB::table('kategoris')->where('id_kategori', $id)->update([
            'nama_kategori' => $request->nama_kategori,
            'updated_at'    => date('Y-m-d H:i:s'),
        ]);

        return response()->json([
            'status'  => 'success',
            'message' => 'Kategori berhasil diperbarui'
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Cek pakai id_kategori
        $kategori = DB::table('kategoris')->where('id_kategori', $id)->first();

        if (!$kategori) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Data kategori tidak ditemukan'
            ], 404);
        }

        // Delete pakai id_kategori
        DB::table('kategoris')->where('id_kategori', $id)->delete();

        return response()->json([
            'status'  => 'success',
            'message' => 'Kategori berhasil dihapus'
        ], 200);
    }
}
