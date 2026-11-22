<?php

namespace App\Providers;

use App\Models\Setting;
use BezhanSalleh\FilamentShield\Facades\FilamentShield;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\View;
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
        // Registers Shield policies Laravel cannot discover on its own (e.g. RolePolicy for Spatie's Role).
        FilamentShield::enforcePolicies();

        RateLimiter::for('contact', function (Request $request): array {
            $tooMany = fn () => back()
                ->withInput()
                ->withErrors(['message' => 'Terlalu banyak pesan terkirim. Silakan coba lagi nanti.']);

            return [
                Limit::perMinute(3)->by($request->ip())->response($tooMany),
                Limit::perDay(20)->by($request->ip())->response($tooMany),
            ];
        });

        View::composer(['components.layouts.app', 'pages.*', 'posts.*', 'portfolios.*'], function ($view): void {
            $view->with('setting', Setting::cached());
        });
    }
}
