<?php

namespace App\Providers;

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;
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

        static $settingsTableExists = null;
        if ($settingsTableExists === null) {
            try {
                $settingsTableExists = Schema::hasTable('settings');
            } catch (\Throwable $e) {
                $settingsTableExists = false;
            }
        }

        $setting = null;
        if ($settingsTableExists) {
            $setting = Cache::remember('site:settings:first', now()->addMinutes(10), function () {
                return Setting::query()->first();
            });
        }

        view()->share('siteSetting', $setting);
    }
}
