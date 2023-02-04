<?php

namespace App\Providers;

use App\Interfaces\DivisiRepositoryInterface;
use App\Interfaces\PosisiRepositoryInterface;
use App\Interfaces\UserProfileRepositoryInterface;
use App\Interfaces\UserRepositoryInterface;
use App\Repository\DivisiRepository;
use App\Repository\PosisiRepository;
use App\Repository\UserProfileRepository;
use App\Repository\UserRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(DivisiRepositoryInterface::class,      DivisiRepository::class);
        $this->app->bind(PosisiRepositoryInterface::class,      PosisiRepository::class);
        $this->app->bind(UserRepositoryInterface::class,        UserRepository::class);
        $this->app->bind(UserProfileRepositoryInterface::class, UserProfileRepository::class);
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
