<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\SupplierController; // <-- Tambahkan import ini

// 🚪 ROUTE PUBLIK (Bisa diakses tanpa login/token oleh Diaz / Postman)
Route::post('/login', [AuthController::class, 'login']);

// Rute untuk mengambil data barang
Route::get('/barang', [BarangController::class, 'index']);

// Rute untuk Master Data: Kategori
Route::get('/kategori', [KategoriController::class, 'index']);
Route::post('/kategori', [KategoriController::class, 'store']);

// Rute untuk Master Data: Supplier (Baru)
Route::get('/supplier', [SupplierController::class, 'index']);
Route::post('/supplier', [SupplierController::class, 'store']);

// Rute untuk Data Transaksional: Barang
Route::get('/barang', [BarangController::class, 'index']);
Route::post('/barang', [BarangController::class, 'store']); // <-- Tambahkan baris ini

// 🔒 ROUTE PROTECTED (Hanya bisa diakses jika membawa Token Sanctum hasil login)
Route::middleware('auth:sanctum')->group(function () {

    // Jalur untuk Logout
    Route::post('/logout', [AuthController::class, 'logout']);
});

// 🌍 MINIMAL PROJECT: HELLO WORLD (Rute Publik Baru)
Route::get('/hello', function () {
    return response()->json([
        'status' => 'success',
        'message' => 'Hello World! API Backend Sistem Inventori berjalan dengan baik.',
        'version' => '1.0.0'
    ]);
});
