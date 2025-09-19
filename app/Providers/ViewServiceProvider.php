<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use App\Models\Inventaris;
use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Share deletedCount ke inventaris.index dan semua view turunannya
        View::composer('inventaris.index', function ($view) {
            $view->with('deletedCount', Inventaris::dihapus()->count());
        });

        // Jika perlu di view lain juga, tambahkan di sini
        View::composer('inventaris.trashed', function ($view) {
            $view->with('deletedCount', Inventaris::dihapus()->count());
        });
    }
}