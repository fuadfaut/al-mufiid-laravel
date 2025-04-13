<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use App\Policies\SantriPolicy;
use App\Policies\NilaiPolicy;
// Import model dan policy lain jika ada
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
              // 'App\Models\Model' => 'App\Policies\ModelPolicy', // Format default
              Santri::class => SantriPolicy::class,
              Nilai::class => NilaiPolicy::class,
              // Daftarkan policy lain di sini:
              // Ustadz::class => UstadzPolicy::class,
              // Kelas::class => KelasPolicy::class,
              // MataPelajaran::class => MataPelajaranPolicy::class,
              // TahunAjaran::class => TahunAjaranPolicy::class,
              // User::class => UserPolicy::class, // Untuk membatasi pengelolaan user
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

                // Definisikan Gate Super Admin (opsional tapi berguna)
        // Ini akan memberikan semua hak akses kepada user dengan role 'admin'
        // tanpa perlu mendefinisikan true di setiap metode policy
        Gate::before(function ($user, $ability) {
            return $user->role === 'admin' ? true : null;
            // Atau jika pakai Spatie Permissions: return $user->hasRole('Admin') ? true : null;
        });
    }
}
