<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // Jika user belum login atau rolenya tidak ada di daftar yang diizinkan, tolak!
        if (!$request->user() || !in_array($request->user()->role, $roles)) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Akses ditolak! Akun Anda tidak memiliki otoritas untuk menu ini.'
            ], 403); // 403 Forbidden
        }

        return $next($request);
    }
}
