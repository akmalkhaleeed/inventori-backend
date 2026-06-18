<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\UserController;

// ==========================================
// 🚪 ROUTE PUBLIK (Bisa diakses tanpa login/token)
// ==========================================
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

// 🌍 MINIMAL PROJECT: HELLO WORLD
Route::get('/hello', function () {
    return response()->json([
        'status' => 'success',
        'message' => 'Hello World! API Backend Sistem Inventori berjalan dengan baik.',
        'version' => '1.0.0'
    ]);
});


// ==========================================
// 🔒 ROUTE PROTECTED (Wajib membawa Token Sanctum hasil login)
// ==========================================
Route::middleware('auth:sanctum')->group(function () {

    // ---------------------------------------------------------
    // 🔓 KELOMPOK 1: AKSES BERSAMA (Admin, Petugas, & Pimpinan) -> READ ONLY
    // ---------------------------------------------------------
    Route::middleware('role:admin,petugas,pimpinan')->group(function () {

        // Pimpinan, Petugas, & Admin sama-sama bisa melihat data untuk kebutuhan laporan / dashboard
        Route::get('/barang', [BarangController::class, 'index']);
        Route::get('/kategori', [KategoriController::class, 'index']);
        Route::get('/supplier', [SupplierController::class, 'index']);
        Route::get('/transaksi', [TransaksiController::class, 'index']); // Ini untuk melihat riwayat barang masuk & keluar
    });

    // ---------------------------------------------------------
    // ⚙️ KELOMPOK 2: AKSES OPERASIONAL DATA (Hanya Admin & Petugas) -> WRITE DATA
    // ---------------------------------------------------------
    Route::middleware('role:admin,petugas')->group(function () {

        // Petugas & Admin bisa menginput data supplier & transaksi baru (Pimpinan TIDAK BISA)
        Route::post('/supplier', [SupplierController::class, 'store']);
        Route::post('/transaksi', [TransaksiController::class, 'store']);
    });

    // ---------------------------------------------------------
    // 🔒 KELOMPOK 3: AKSES EKSKLUSIF MANAJEMEN (Hanya Boleh Diakses Admin)
    // ---------------------------------------------------------
    Route::middleware('role:admin')->group(function () {

        // 👥 Kelompok CRUD Manajemen User (Hanya Admin)
        Route::get('/user', [UserController::class, 'index']);
        Route::post('/user', [UserController::class, 'store']);
        Route::get('/user/{id}', [UserController::class, 'show']);
        Route::put('/user/{id}', [UserController::class, 'update']);
        Route::delete('/user/{id}', [UserController::class, 'destroy']);

        // 📂 Kelompok Manipulasi Data Kategori (Hanya Admin)
        Route::post('/kategori', [KategoriController::class, 'store']);
        Route::put('/kategori/{id}', [KategoriController::class, 'update']);
        Route::delete('/kategori/{id}', [KategoriController::class, 'destroy']);

        // 🚚 Kelompok Manipulasi Data Supplier sisa Edit & Hapus (Hanya Admin)
        Route::put('/supplier/{id}', [SupplierController::class, 'update']);
        Route::delete('/supplier/{id}', [SupplierController::class, 'destroy']);

        // 📦 Kelompok Manipulasi Data Barang (Tambah, Edit, Hapus)
        Route::post('/barang', [BarangController::class, 'store']);
        Route::put('/barang/{id}', [BarangController::class, 'update']);
        Route::delete('/barang/{id}', [BarangController::class, 'destroy']);
    });

    // 🚪 Jalur untuk Logout (Semua user yang login bisa logout)
    Route::post('/logout', [AuthController::class, 'logout']);
});
