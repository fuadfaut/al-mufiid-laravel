<?php

namespace App\Providers;

use App\Models\Santri;
use App\Observers\SantriObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register the Santri observer
        Santri::observe(SantriObserver::class);
    }
}
