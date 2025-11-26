<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

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
        if($this->app->environment('production') || $this->app->environment('local')) {
        // En local esto puede romper los estilos si no tienes certificado,
        // pero demuestra la implementación del protocolo.
        // URL::forceScheme('https'); 
    }
    }
}
