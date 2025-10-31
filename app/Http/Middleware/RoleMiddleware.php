<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Middleware ini bisa dipanggil langsung lewat app(RoleMiddleware::class)
     * tanpa perlu didaftarkan di Kernel.php
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        // Jika user belum login → lempar ke halaman login
        if (!Auth::check()) {
            return redirect('/login');
        }

        // Ambil role user yang login
        $userRole = Auth::user()->role;

        // Cek apakah role user termasuk role yang diizinkan
        if (!in_array($userRole, $roles)) {
            // Jika tidak cocok, tampilkan error 403
            abort(403, 'Akses ditolak — kamu tidak memiliki izin untuk halaman ini.');
        }

        // Jika lolos, lanjut ke proses berikutnya
        return $next($request);
    }
}
