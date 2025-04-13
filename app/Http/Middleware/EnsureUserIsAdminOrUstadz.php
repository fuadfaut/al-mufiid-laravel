<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsAdminOrUstadz
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Ambil user yang terautentikasi
        $user = Auth::user();

        // Jika user tidak login atau BUKAN admin DAN BUKAN ustadz
        if (!$user || (!$user->isAdmin() && !$user->isUstadz())) {
            // Jika tidak ada user (seharusnya sudah ditangani Authenticate::class)
             if (!$user) {
                 return redirect()->guest(filament()->getLoginUrl());
             }
            // Jika ada user tapi bukan Admin/Ustadz (misal: Santri coba akses)
             abort(403, 'Akses Ditolak.'); // Tampilkan halaman error 403 Forbidden
        }

        // Jika user adalah Admin atau Ustadz, lanjutkan request
        return $next($request);
    }
}