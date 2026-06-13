<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        // 1. Validasi input sesuai dengan skema tabel user kamu
        $request->validate([
            'name'     => 'required|string|max:255',
            'username' => 'required|string|unique:users,username',
            'email'    => 'required|string|email|unique:users,email',
            'password' => 'required|string|min:6',
            'role'     => 'required|in:admin,petugas,pimpinan',
        ]);

        // 2. Simpan user baru menggunakan Eloquent Model agar serasi
        $user = User::create([
            'name'     => $request->name,
            'username' => $request->username,
            'email'    => $request->email,
            'password' => Hash::make($request->password), // Enkripsi password demi keamanan
            'role'     => $request->role,
        ]);

        // 3. Kirim respon sukses ke Postman / Diaz
        return response()->json([
            'status'  => 'success',
            'message' => 'User baru berhasil didaftarkan!',
            'data'    => [
                'name'     => $user->name,
                'username' => $user->username,
                'role'     => $user->role
            ]
        ], 201); // 201 Created
    }

    public function login(Request $request)
    {
        // 1. Validasi input dari Frontend (Diaz)
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        // 2. Cek apakah username & password cocok dengan database
        if (!Auth::attempt($request->only('username', 'password'))) {
            return response()->json([
                'message' => 'Username atau password salah!'
            ], 401);
        }

        // 3. Ambil data user yang berhasil login
        $user = User::where('username', $request->username)->firstOrFail();

        // 4. Buat Token Akses (Menggunakan fitur Sanctum bawaan Laravel)
        $token = $user->createToken('auth_token')->plainTextToken;

        // 5. Kirim respon sukses ke Frontend beserta Role-nya
        return response()->json([
            'message' => 'Login berhasil!',
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => [
                // PERBAIKAN DI SINI: Menambahkan ID ke dalam response
                // Kita gunakan ?? agar mendukung primary key 'id' maupun 'id_user'
                'id_user'  => $user->id_user ?? $user->id,
                'name'     => $user->name,
                'username' => $user->username,
                'role'     => $user->role
            ]
        ]);
    }

    public function logout(Request $request)
    {
        // Hapus token saat user melakukan logout
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Berhasil logout'
        ]);
    }
}
