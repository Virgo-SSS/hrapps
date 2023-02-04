<?php

namespace Tests;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Plannr\Laravel\FastRefreshDatabase\Traits\FastRefreshDatabase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication, FastRefreshDatabase;

    public function makeUserArray()
    {
        $user = User::factory()->make()->toArray();
        $user['password'] = '123456';

        return $user;
    }
}
