<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // <-- Jangan lupa tambahkan ini

class KategoriController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Mengambil semua data kategori
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
        // 1. Validasi inputan dari Front-End
        $request->validate([
            'nama_kategori' => 'required|string|max:255',
        ]);

        // 2. Simpan data kategori baru ke database
        DB::table('kategoris')->insert([
            'nama_kategori' => $request->nama_kategori,
            'created_at'    => date('Y-m-d H:i:s'),
            'updated_at'    => date('Y-m-d H:i:s'),
        ]);

        // 3. Berikan respons sukses
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
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
