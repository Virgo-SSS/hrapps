<?php

namespace App\Providers;

use App\Interfaces\CutiRepositoryInterface;
use App\Repository\CutiRepository;
use Illuminate\Support\ServiceProvider;

class CutiServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(CutiRepositoryInterface::class,CutiRepository::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
