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
Route::post('/register', [AuthController::class, 'register']); // <-- Ini yang tadi bikin 404 Not Found

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

    // 👥 1. Kelompok CRUD Manajemen User (Hanya boleh diakses oleh Admin)
    Route::get('/user', [UserController::class, 'index']);       // Lihat Semua Karyawan
    Route::post('/user', [UserController::class, 'store']);      // Tambah Karyawan Baru
    Route::get('/user/{id}', [UserController::class, 'show']);   // Lihat Detail 1 Karyawan
    Route::put('/user/{id}', [UserController::class, 'update']);  // Edit Karyawan
    Route::delete('/user/{id}', [UserController::class, 'destroy']); // Hapus Karyawan

    // 📂 2. Rute untuk Master Data: Kategori
    Route::get('/kategori', [KategoriController::class, 'index']);
    Route::post('/kategori', [KategoriController::class, 'store']);
    Route::put('/kategori/{id}', [KategoriController::class, 'update']);
    Route::delete('/kategori/{id}', [KategoriController::class, 'destroy']);

    // 🚚 3. Rute untuk Master Data: Supplier
    Route::get('/supplier', [SupplierController::class, 'index']);
    Route::post('/supplier', [SupplierController::class, 'store']);
    Route::put('/supplier/{id}', [SupplierController::class, 'update']);
    Route::delete('/supplier/{id}', [SupplierController::class, 'destroy']);

    // 📦 4. Rute untuk Master Data: Barang
    Route::get('/barang', [BarangController::class, 'index']);
    Route::post('/barang', [BarangController::class, 'store']);
    Route::put('/barang/{id}', [BarangController::class, 'update']);
    Route::delete('/barang/{id}', [BarangController::class, 'destroy']);

    // 📊 5. Rute untuk Data Transaksional: Riwayat Transaksi & Logika Stok otomatis
    Route::get('/transaksi', [TransaksiController::class, 'index']);
    Route::post('/transaksi', [TransaksiController::class, 'store']);

    // 🚪 6. Jalur untuk Logout
    Route::post('/logout', [AuthController::class, 'logout']);
});
