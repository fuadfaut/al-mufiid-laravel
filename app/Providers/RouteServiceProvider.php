<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to the "home" route for your application.
     *
     * This is used as a fallback when the intended destination is not specified.
     * We'll use a helper method to determine the actual redirect path based on user role.
     *
     * @var string
     */
    public const HOME = '/';

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     */
    public function boot(): void
    {
        $this->configureRateLimiting();

        $this->routes(function () {
            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api.php'));

            Route::middleware('web')
                ->group(base_path('routes/web.php'));
        });
    }

    /**
     * Configure the rate limiters for the application.
     */
    protected function configureRateLimiting(): void
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });
    }

    /**
     * Get the redirect path based on user role.
     *
     * @return string
     */
    public static function getHomeForUser(): string
    {
        if (auth()->check()) {
            $user = auth()->user();

            if ($user->isAdmin() || $user->isUstadz()) {
                return config('filament.path', '/admin');
            } elseif ($user->isSantri()) {
                return '/santri/dashboard';
            }
        }

        return self::HOME;
    }
}
