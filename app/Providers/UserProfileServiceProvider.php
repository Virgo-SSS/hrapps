<?php

namespace App\Providers;

use App\Interfaces\UserProfileRepositoryInterface;
use App\Repository\UserProfileRepository;
use Illuminate\Support\ServiceProvider;

class UserProfileServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(UserProfileRepositoryInterface::class,UserProfileRepository::class);
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
