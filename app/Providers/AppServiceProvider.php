<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use App\Models\Category;

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

        // Share active categories to frontend views (for header search)
        // Uses cache to avoid hitting DB on every request.
        View::composer(['frontend.*'], function ($view) {
            $headerCategories = [];
            try {
                $headerCategories = Cache::remember('header_categories', now()->addMinutes(10), function () {
                    // Keep it lean: only fields you need in the header
                    return Category::query()
                        ->where('status', '1')
                        ->orderBy('name')
                        ->get(['id','slug','name']);
                });
            } catch (\Throwable $e) {
                // swallow if migrations not run or table missing
                $headerCategories = [];
            }

            $view->with('headerCategories', $headerCategories);
        });
    }
}
