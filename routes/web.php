<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RaporController;          // Controller untuk aksi rapor dari Admin/Ustadz
use App\Http\Controllers\Santri\DashboardController as SantriDashboardController; // Controller untuk dashboard Santri (gunakan alias agar jelas)
use App\Http\Middleware\EnsureUserIsAdminOrUstadz;  // Middleware untuk route Admin/Ustadz jika perlu (opsional)
use App\Http\Middleware\EnsureUserIsSantri;         // Middleware khusus Santri
use App\Models\Santri;                              // Untuk Route Model Binding
use App\Models\TahunAjaran;                         // Untuk Route Model Binding

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
| Rute publik dan autentikasi dasar.
*/

// Halaman utama
Route::get('/', function () {
    // Jika sudah login, redirect berdasarkan role, jika belum, tampilkan welcome
    if (auth()->check()) {
        $user = auth()->user();
        if ($user->isAdmin() || $user->isUstadz()) {
            // Redirect ke panel admin Filament
            return redirect(config('filament.path', '/admin'));
        } elseif ($user->isSantri()) {
            // Redirect ke dashboard santri
            return redirect()->route('santri.dashboard');
        }
    }
    // Jika belum login, tampilkan halaman welcome
    return view('welcome');
});

// Rute Autentikasi Bawaan (Login, Register, dll. dari Breeze/UI/Jetstream)
require __DIR__.'/auth.php';

/*
|--------------------------------------------------------------------------
| Rute untuk User yang Sudah Login (Umum)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    // Route Profile bawaan (misal dari Breeze)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    // Route password biasanya terpisah, contoh:
    // Route::put('password', [PasswordController::class, 'update'])->name('password.update');


    // Fallback route for /dashboard to redirect based on user role
    Route::get('/dashboard', function () {
        return redirect(\App\Providers\RouteServiceProvider::getHomeForUser());
    });

});

/*
|--------------------------------------------------------------------------
| Rute untuk Akses Rapor oleh Admin / Ustadz (Dipanggil dari Filament)
|--------------------------------------------------------------------------
| Dilindungi oleh middleware 'auth'. Middleware spesifik panel Filament
| sudah melindungi akses ke panel itu sendiri. Role check di sini opsional
| tapi bisa menambah lapisan keamanan.
*/
Route::middleware(['auth' /*, EnsureUserIsAdminOrUstadz::class*/]) // Pastikan user login. Role check opsional.
     ->prefix('admin') // Opsional: Tambahkan prefix agar URL lebih jelas
    ->group(function () {

    // Gunakan Route Model Binding untuk Santri dan TahunAjaran
    Route::get(
        // '/admin/rapor/{santri}/preview/{tahun_ajaran}', // Contoh jika pakai prefix admin
        '/rapor/{santri}/preview/{tahun_ajaran}', // Tanpa prefix
        [RaporController::class, 'preview']
    )   ->where('santri', '[0-9]+') // Pastikan ID santri adalah angka
        ->where('tahun_ajaran', '[0-9]+') // Pastikan ID tahun ajaran adalah angka
        ->name('admin.rapor.preview'); // Beri nama unik (prefix 'admin.')

    Route::get(
        // '/admin/rapor/{santri}/cetak/{tahun_ajaran}', // Contoh jika pakai prefix admin
        '/rapor/{santri}/cetak/{tahun_ajaran}', // Tanpa prefix
        [RaporController::class, 'cetak']
    )   ->where('santri', '[0-9]+')
        ->where('tahun_ajaran', '[0-9]+')
        ->name('admin.rapor.cetak'); // Beri nama unik

    // PENTING: Pastikan URL yang Anda generate di Action Filament (SantriResource)
    // merujuk ke nama route ini (admin.rapor.preview / admin.rapor.cetak)
    // dan menyertakan parameter {santri} dan {tahun_ajaran}.
});


/*
|--------------------------------------------------------------------------
| Rute Khusus untuk Santri
|--------------------------------------------------------------------------
| Dilindungi oleh middleware 'auth' dan 'EnsureUserIsSantri'.
*/

// Rute untuk halaman menunggu konfirmasi (hanya perlu auth dan role.santri)
Route::middleware(['auth', EnsureUserIsSantri::class])
    ->prefix('santri')
    ->name('santri.')
    ->group(function () {
        // Halaman menunggu konfirmasi
        Route::get('/waiting-confirmation', [\App\Http\Controllers\Santri\WaitingConfirmationController::class, 'index'])
            ->name('waiting-confirmation');
    });

// Rute yang memerlukan santri sudah terkonfirmasi
Route::middleware(['auth', EnsureUserIsSantri::class, 'santri.confirmed'])
    ->prefix('santri') // URL diawali dengan /santri/...
    ->name('santri.') // Nama route diawali dengan santri. (e.g., santri.dashboard)
    ->group(function () {

        // Dashboard utama santri
        Route::get('/dashboard', [SantriDashboardController::class, 'index'])->name('dashboard');

        // Lihat Rapor (HTML Preview) untuk tahun ajaran tertentu
        // Menggunakan Route Model Binding untuk TahunAjaran
        Route::get('/rapor/{tahun_ajaran}/preview', [SantriDashboardController::class, 'previewRapor'])
            ->where('tahun_ajaran', '[0-9]+') // Pastikan ID adalah angka
            ->name('rapor.preview'); // Nama: santri.rapor.preview

        // Cetak Rapor (PDF) untuk tahun ajaran tertentu
        // Menggunakan Route Model Binding untuk TahunAjaran
        Route::get('/rapor/{tahun_ajaran}/cetak', [SantriDashboardController::class, 'cetakRapor'])
            ->where('tahun_ajaran', '[0-9]+') // Pastikan ID adalah angka
            ->name('rapor.cetak'); // Nama: santri.rapor.cetak

        // Tambahkan route lain khusus santri di sini (misal: lihat profil santri sendiri)
        // Route::get('/profil', [SantriDashboardController::class, 'profil'])->name('profil');
});