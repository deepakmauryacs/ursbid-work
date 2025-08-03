<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

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
        // Avoid issues with older MySQL versions
        Schema::defaultStringLength(191);

        // Load web settings and share with all views
        try {
            $webSettings = DB::table('web_settings')->first();
            View::share('webSettings', $webSettings);
        } catch (\Exception $e) {
            // Handle exception (e.g., during migration when table doesn't exist yet)
            View::share('webSettings', null);
        }
    }
}
