<?php

namespace Tests;

use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Plannr\Laravel\FastRefreshDatabase\Traits\FastRefreshDatabase;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

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

    public function createUserWithRoles(string $roles): User
    {
        $user = User::factory()->create();

        Role::create(['name' => $roles]);
        $user->assignRole($roles);

        return $user;
    }

    public function assignPermission(string $permission, User $user): void
    {
        Permission::create(['name' => $permission]);

        $user->givePermissionTo($permission);
    }
}
