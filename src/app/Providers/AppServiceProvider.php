<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        URL::forceScheme('https');

        RateLimiter::for('chat', function (Request $request) {
            return [
                Limit::perMinute(10)->by($request->ip()),
                Limit::perHour(50)->by('h:' . $request->ip()),
                Limit::perDay(200)->by('d:' . $request->ip()),
            ];
        });
    }
}
