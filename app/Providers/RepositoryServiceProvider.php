<?php

namespace App\Providers;

use App\Interfaces\CargaArchivoRepositoryInterface;
use App\Interfaces\UsuarioRepositoryInterface;
use App\Interfaces\VentaRepositoryInterface;
use App\Repositories\CargaArchivoRepository;
use App\Repositories\UsuarioRepository;
use App\Repositories\VentaRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(UsuarioRepositoryInterface::class,UsuarioRepository::class);
        $this->app->bind(CargaArchivoRepositoryInterface::class,CargaArchivoRepository::class);
        $this->app->bind(VentaRepositoryInterface::class,VentaRepository::class);

    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
