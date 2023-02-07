<?php

namespace App\Providers;

use App\Interfaces\PosisiRepositoryInterface;
use App\Repository\PosisiRepository;
use Illuminate\Support\ServiceProvider;

class PosisiServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(PosisiRepositoryInterface::class,PosisiRepository::class);
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
