<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
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
                'name' => $user->name,
                'username' => $user->username,
                'role' => $user->role // Ini penting untuk Diaz mengatur hak akses halaman di React!
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
