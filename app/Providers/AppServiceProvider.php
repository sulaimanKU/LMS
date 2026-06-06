<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
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
        \Illuminate\Pagination\Paginator::useBootstrapFive();

        if (\Illuminate\Support\Facades\Schema::hasTable('system_settings')) {
            $settings = \App\Models\SystemSetting::pluck('value', 'key')->toArray();
            \Illuminate\Support\Facades\View::share('systemSettings', $settings);
        } else {
            \Illuminate\Support\Facades\View::share('systemSettings', []);
        }
    }
}
