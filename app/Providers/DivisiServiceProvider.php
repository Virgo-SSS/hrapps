<?php

namespace App\Providers;

use App\Interfaces\DivisiRepositoryInterface;
use App\Repository\DivisiRepository;
use Illuminate\Support\ServiceProvider;

class DivisiServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(DivisiRepositoryInterface::class,DivisiRepository::class);
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
