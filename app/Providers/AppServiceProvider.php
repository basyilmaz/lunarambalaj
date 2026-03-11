<?php

namespace App\Providers;

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        if (app()->environment('production')) {
            URL::forceScheme('https');
        }

        $setting = null;
        try {
            $setting = Cache::remember('site:settings:first', now()->addMinutes(10), function () {
                return Setting::query()->first();
            });
        } catch (\Throwable $e) {
            // During first bootstrap/migration window the settings table may not exist yet.
            $setting = null;
        }

        view()->share('siteSetting', $setting);
    }
}
