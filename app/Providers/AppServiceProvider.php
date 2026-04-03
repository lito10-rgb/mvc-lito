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
    //     //
    //      Paginator::useBootstrapFive(); // o useBootstrapFour() si usas v4
    //      ///
    //      $categorias = Categoria::with('subcategorias')->get();
    // View::share('categorias', $categorias);
    // 
           Paginator::useBootstrapFive();

    view()->composer('*', function ($view) {
        $view->with('categorias', Categoria::with('subcategorias')->get());
    });
    }
}
