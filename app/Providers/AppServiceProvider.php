<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

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

        // Share web settings globally (safe if table not created yet)
        try {
            $webSettings = DB::table('web_settings')->first();
            View::share('webSettings', $webSettings);
        } catch (\Throwable $e) {
            View::share('webSettings', null);
        }

        // Share active categories to ALL views (so header gets them)
        try {
            $headerCategories = Cache::remember('header_categories', now()->addMinutes(10), function () {
                return DB::table('categories')
                    ->where('status', 1) // Adjust if your column is VARCHAR
                    ->orderBy('name')
                    ->get(['id', 'slug', 'name']);
            });
        } catch (\Throwable $e) {
            // swallow if migrations not run or table missing
            $headerCategories = collect();
        }

        View::share('headerCategories', $headerCategories);
    }
}
