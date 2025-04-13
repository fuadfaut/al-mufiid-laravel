<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsSantri
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        // Jika user tidak login atau BUKAN santri atau tidak terhubung ke data santri
        if (!$user || !$user->isSantri() || !$user->santri_id) {
             // Redirect ke login jika tidak terautentikasi
            if (!$user) {
                return redirect()->route('login'); // Ganti 'login' jika nama route login beda
            }
             // Jika user login tapi bukan Santri yg valid
            abort(403, 'Anda tidak diizinkan mengakses halaman ini.');
        }

        // Jika user adalah Santri valid, lanjutkan
        return $next($request);
    }
}