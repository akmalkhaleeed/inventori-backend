<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth; // <-- Wajib di-import untuk cek session login

class UserController extends Controller
{
    /**
     * FUNGSI PENGAMAN: Otomatis menolak jika yang mengakses bukan Admin
     */
    private function batasiHanyaAdmin()
    {
        $userLogin = Auth::user();
        if (!$userLogin || $userLogin->role !== 'admin') {
            return response()->json([
                'status'  => 'error',
                'message' => 'Akses ditolak! Hanya Admin yang berhak mengelola data user/karyawan.'
            ], 403); // 403 Forbidden
        }
        return null;
    }

    /**
     * 1. READ (Melihat semua data user)
     */
    public function index()
    {
        $keamanan = $this->batasiHanyaAdmin();
        if ($keamanan) return $keamanan;

        $users = User::orderBy('created_at', 'desc')->get();

        return response()->json([
            'status'  => 'success',
            'message' => 'Daftar semua user berhasil diambil.',
            'data'    => $users
        ], 200);
    }

    /**
     * 2. CREATE (Menambah user baru - Hanya Admin yang Bisa)
     */
    public function store(Request $request)
    {
        $keamanan = $this->batasiHanyaAdmin();
        if ($keamanan) return $keamanan;

        $request->validate([
            'name'     => 'required|string|max:255',
            'username' => 'required|string|unique:users,username',
            'email'    => 'required|string|email|unique:users,email',
            'password' => 'required|string|min:6',
            'role'     => 'required|in:admin,petugas,pimpinan',
        ]);

        $userBaru = User::create([
            'name'     => $request->name,
            'username' => $request->username,
            'email'    => $request->email,
            'password' => Hash::make($request->password), // Enkripsi password
            'role'     => $request->role,
        ]);

        return response()->json([
            'status'  => 'success',
            'message' => 'User baru berhasil didaftarkan oleh Admin!',
            'data'    => $userBaru
        ], 201);
    }

    /**
     * 3. READ DETAIL (Melihat 1 user berdasarkan ID)
     */
    public function show(string $id)
    {
        $keamanan = $this->batasiHanyaAdmin();
        if ($keamanan) return $keamanan;

        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'status'  => 'error',
                'message' => 'User tidak ditemukan!'
            ], 404);
        }

        return response()->json([
            'status'  => 'success',
            'data'    => $user
        ], 200);
    }

    /**
     * 4. UPDATE (Mengubah data user)
     */
    public function update(Request $request, string $id)
    {
        $keamanan = $this->batasiHanyaAdmin();
        if ($keamanan) return $keamanan;

        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'status'  => 'error',
                'message' => 'User tidak ditemukan!'
            ], 404);
        }

        $request->validate([
            'name'     => 'required|string|max:255',
            'username' => 'required|string|unique:users,username,' . $id,
            'email'    => 'required|string|email|unique:users,email,' . $id,
            'password' => 'nullable|string|min:6', // Boleh kosong kalau tak mau ganti password
            'role'     => 'required|in:admin,petugas,pimpinan',
        ]);

        $dataUpdate = [
            'name'     => $request->name,
            'username' => $request->username,
            'email'    => $request->email,
            'role'     => $request->role,
        ];

        if ($request->filled('password')) {
            $dataUpdate['password'] = Hash::make($request->password);
        }

        $user->update($dataUpdate);

        return response()->json([
            'status'  => 'success',
            'message' => 'Data user berhasil diperbarui!',
            'data'    => $user
        ], 200);
    }

    /**
     * 5. DELETE (Menghapus user)
     */
    public function destroy(string $id)
    {
        $keamanan = $this->batasiHanyaAdmin();
        if ($keamanan) return $keamanan;

        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'status'  => 'error',
                'message' => 'User tidak ditemukan!'
            ], 404);
        }

        // Biar admin nggak sengaja kehapus akun sendiri pas lagi login
        if (Auth::id() == $id) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Aksi ditolak! Kamu tidak bisa menghapus akun sendiri.'
            ], 400);
        }

        $user->delete();

        return response()->json([
            'status'  => 'success',
            'message' => 'User ' . $user->name . ' berhasil dihapus.'
        ], 200);
    }
}
