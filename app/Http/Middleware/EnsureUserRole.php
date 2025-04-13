<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Import facade Auth
use Symfony\Component\HttpFoundation\Response;

class EnsureUserRole
{
    /**
     * Handle an incoming request.
     *
     * Memeriksa apakah pengguna yang terautentikasi memiliki salah satu peran (role) yang ditentukan.
     *
     * @param  \Illuminate\Http\Request  $request Request yang masuk.
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next Middleware selanjutnya atau aksi controller.
     * @param  string  ...$roles Daftar nama peran (role) yang diizinkan untuk mengakses route. Contoh: 'admin', 'ustadz'
     * @return \Symfony\Component\HttpFoundation\Response Respon yang dihasilkan.
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // 1. Periksa apakah pengguna sudah terautentikasi.
        //    Meskipun middleware 'auth' biasanya berjalan lebih dulu, ini sebagai pengaman.
        if (!Auth::check()) {
            // Jika belum login, arahkan ke halaman login.
            // Sebaiknya gunakan route name jika Anda sudah mendefinisikannya.
            return redirect()->route('login');
        }

        // 2. Dapatkan pengguna yang sedang terautentikasi.
        $user = Auth::user();

        // 3. Periksa apakah peran pengguna ada dalam daftar peran yang diizinkan.
        //    Diasumsikan model User memiliki atribut/kolom 'role'.
        if (!in_array($user->role, $roles)) {
            // 4. Jika peran pengguna tidak diizinkan:
            //    - Opsi A: Batalkan dengan error 403 Forbidden.
            //      abort(403, 'Aksi tidak diizinkan.');

            //    - Opsi B: Arahkan ke halaman tertentu (misalnya home) dengan pesan error.
            //      Ini seringkali lebih ramah pengguna.
            return redirect('/')->with('error', 'Anda tidak memiliki izin untuk mengakses halaman ini.');

            //    - Opsi C: Arahkan kembali ke halaman sebelumnya dengan pesan error.
            //      Hati-hati karena bisa menyebabkan loop redirect dalam beberapa kasus.
            //      return redirect()->back()->with('error', 'Anda tidak memiliki izin untuk mengakses halaman ini.');
        }

        // 5. Jika peran pengguna diizinkan, lanjutkan request ke middleware/controller berikutnya.
        return $next($request);
    }
}