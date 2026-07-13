<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
///
use Illuminate\Support\Facades\View;
use App\Models\Categoria;

require_once app_path('Helpers/negocio.php');

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->commands([
            \App\Console\Commands\MigrarCafePeruano::class,
            \App\Console\Commands\CopiarImagenesCafe::class,
        ]);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
           Paginator::useBootstrapFive();
           $this->loadMigrationsFrom(database_path('migrations/exim'));

    view()->composer('*', function ($view) {
        $negocioId = negocio_actual_id();
        $view->with('menuCategorias', Categoria::whereHas('negocios', fn($q) => $q->where('negocio_id', $negocioId))
            ->with(['subcategorias' => fn($q) => $q->whereHas('negocios', fn($q2) => $q2->where('negocio_id', $negocioId))])
            ->get());
    });
    }
}
