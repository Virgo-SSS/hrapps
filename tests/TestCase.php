<?php

namespace Tests;

use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Plannr\Laravel\FastRefreshDatabase\Traits\FastRefreshDatabase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication, FastRefreshDatabase;

    public function makeUserArray(): Array
    {
        $user = User::factory()->make()->toArray();
        $user['password'] = '123456';

        return $user;
    }

    public function makeUserWithProfile(): User
    {
        $user = User::factory()->create();

        UserProfile::factory()->create([
            'user_id' => $user->id,
        ]);

        return $user;
    }
}
