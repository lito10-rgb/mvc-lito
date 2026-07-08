<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
///
use Illuminate\Support\Facades\View;
use App\Models\Categoria;

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
           Paginator::useBootstrapFive();
           $this->loadMigrationsFrom(database_path('migrations/exim'));

    view()->composer('*', function ($view) {
        $view->with('menuCategorias', Categoria::with('subcategorias')->get());
    });
    }
}
