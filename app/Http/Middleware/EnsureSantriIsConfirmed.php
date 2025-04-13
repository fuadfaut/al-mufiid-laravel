<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureSantriIsConfirmed
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        // Jika user tidak login atau bukan santri, redirect ke login
        if (!$user || !$user->isSantri()) {
            return redirect()->route('login');
        }

        // Ambil data santri
        $santri = $user->santri;

        // Jika santri tidak ditemukan atau belum dikonfirmasi
        if (!$santri || !$santri->is_confirmed) {
            // Redirect ke halaman menunggu konfirmasi
            return redirect()->route('santri.waiting-confirmation');
        }

        return $next($request);
    }
}
