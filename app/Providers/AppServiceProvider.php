<?php

namespace App\Providers;

use App\Support\SiteSettings;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(SiteSettings::class);
    }

    public function boot(): void
    {
        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }

        Carbon::setLocale('id');
        setlocale(LC_TIME, 'id_ID.UTF-8', 'id_ID', 'Indonesian');

        // Pengaturan situs tersedia sebagai $site di semua view publik dan partial-nya.
        View::share('site', $this->app->make(SiteSettings::class));

        $this->configureRateLimiting();
    }

    /** Pembatas laju untuk form publik, sebagai lapisan anti-spam selain honeypot. */
    private function configureRateLimiting(): void
    {
        RateLimiter::for('konsultasi', fn (Request $request) => [
            Limit::perMinutes(60, 5)->by($request->ip()),
            Limit::perDay(15)->by($request->ip()),
        ]);

        RateLimiter::for('bukti-pembayaran', fn (Request $request) => [
            Limit::perMinutes(60, 10)->by($request->ip()),
        ]);
    }
}
