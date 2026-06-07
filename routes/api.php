<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

// 🚪 ROUTE PUBLIK (Bisa diakses tanpa login/token oleh Diaz / Postman)
Route::post('/login', [AuthController::class, 'login']);

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
