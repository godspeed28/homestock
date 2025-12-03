<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

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
        // Untuk development
        if (app()->environment('local')) {
            // Pastikan pakai HTTP dengan port 8000
            URL::forceScheme('http');
            config(['app.url' => 'http://localhost:8000']);
        }
        // Untuk production
        elseif (app()->environment('production')) {
            // Force HTTPS di production
            URL::forceScheme('https');
        }
    }
}
