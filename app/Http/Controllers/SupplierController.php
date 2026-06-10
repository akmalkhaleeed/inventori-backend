<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // <-- Wajib ditambahkan

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Mengambil semua data supplier
        $supplier = DB::table('suppliers')->get();

        return response()->json([
            'status'  => 'success',
            'message' => 'Daftar supplier berhasil diambil',
            'data'    => $supplier
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // 1. Validasi inputan dari Front-End
        // alamat dan no_telp kita buat 'nullable' sesuai dengan migration kamu
        $request->validate([
            'nama_supplier' => 'required|string|max:255',
            'alamat'        => 'nullable|string',
            'no_telp'       => 'nullable|string|max:20',
        ]);

        // 2. Simpan data supplier baru ke database
        DB::table('suppliers')->insert([
            'nama_supplier' => $request->nama_supplier,
            'alamat'        => $request->alamat,
            'no_telp'       => $request->no_telp,
            'created_at'    => date('Y-m-d H:i:s'),
            'updated_at'    => date('Y-m-d H:i:s'),
        ]);

        // 3. Berikan respons sukses
        return response()->json([
            'status'  => 'success',
            'message' => 'Supplier berhasil ditambahkan'
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
