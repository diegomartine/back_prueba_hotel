<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

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
        // Cargar las rutas de la API manualmente
        Route::prefix('api')  // Prefijo 'api' para las rutas
             ->middleware('api')  // Middleware 'api'
             ->group(base_path('routes/api.php'));  // Cargar las rutas de api.php
    }
}
