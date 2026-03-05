<?php

namespace App\Providers;

use App\Models\Setting;
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

        view()->composer('*', function ($view): void {
            $locale = app()->getLocale();
            $setting = null;

            if (Schema::hasTable('settings')) {
                $setting = Setting::query()->first();
            }

            $view->with('siteLocale', $locale)
                ->with('siteSetting', $setting)
                ->with('attribution', session('attribution', []));
        });
    }
}
