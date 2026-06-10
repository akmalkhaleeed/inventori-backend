<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
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
        $request->validate([
            'nama_supplier' => 'required|string|max:255',
            'alamat'        => 'nullable|string',
            'no_telp'       => 'nullable|string|max:20',
        ]);

        DB::table('suppliers')->insert([
            'nama_supplier' => $request->nama_supplier,
            'alamat'        => $request->alamat,
            'no_telp'       => $request->no_telp,
            'created_at'    => date('Y-m-d H:i:s'),
            'updated_at'    => date('Y-m-d H:i:s'),
        ]);

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
        // 1. Validasi data baru (alamat & no_telp boleh nullable)
        $request->validate([
            'nama_supplier' => 'required|string|max:255',
            'alamat'        => 'nullable|string',
            'no_telp'       => 'nullable|string|max:20',
        ]);

        // 2. Cek apakah data supplier ada di database
        $supplier = DB::table('suppliers')->where('id', $id)->first();

        if (!$supplier) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Data supplier tidak ditemukan'
            ], 404);
        }

        // 3. Eksekusi Update
        DB::table('suppliers')->where('id', $id)->update([
            'nama_supplier' => $request->nama_supplier,
            'alamat'        => $request->alamat,
            'no_telp'       => $request->no_telp,
            'updated_at'    => date('Y-m-d H:i:s'),
        ]);

        return response()->json([
            'status'  => 'success',
            'message' => 'Supplier berhasil diperbarui'
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // 1. Cek apakah data supplier ada
        $supplier = DB::table('suppliers')->where('id', $id)->first();

        if (!$supplier) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Data supplier tidak ditemukan'
            ], 404);
        }

        // 2. Eksekusi Hapus
        DB::table('suppliers')->where('id', $id)->delete();

        return response()->json([
            'status'  => 'success',
            'message' => 'Supplier berhasil dihapus'
        ], 200);
    }
}
