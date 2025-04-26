<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;


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
        // Use Bootstrap pagination views
        Paginator::useBootstrapFive(); // For Bootstrap 5
        // OR
        Paginator::useBootstrapFour(); // For Bootstrap 4
    }
}
