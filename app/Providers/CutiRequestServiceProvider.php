<?php

namespace App\Providers;

use App\Interfaces\CutiRequestRepositoryInterface;
use App\Repository\CutiRequestRepository;
use Illuminate\Support\ServiceProvider;

class CutiRequestServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(CutiRequestRepositoryInterface::class, CutiRequestRepository::class);
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
