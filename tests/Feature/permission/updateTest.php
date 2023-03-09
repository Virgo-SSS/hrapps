<?php

namespace Tests\Feature\permission;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;

class updateTest extends TestCase
{
    public function test_user_cant_update_permission_if_not_authenticated(): void
    {
        $response = $this->put(route('permission.update', 1));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }

    public function test_update_permission_field_name_is_required(): void
    {
        $user = $this->createUserWithRoles('super admin');
        $this->actingAs($user);

        $permission = Permission::create(['name' => 'permission.test']);

        $response = $this->put(route('permission.update', $permission->id), [
            'name' => '',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors('name');
    }

    public function test_update_permission_field_name_must_unique(): void
    {
        $user = $this->createUserWithRoles('super admin');
        $this->actingAs($user);

        Permission::create(['name' => 'permission.test']);
        $permission = Permission::create(['name' => 'permission.test2']);

        $response = $this->put(route('permission.update', $permission->id), [
            'name' => 'permission.test',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors('name');
    }

    public function test_user_cant_update_permission_without_permission(): void
    {
        $user = $this->createUserWithRoles('invalidroles');
        $this->assignPermission('invalidPermission', $user);
        $this->actingAs($user);

        $permission = Permission::create(['name' => 'permission.test']);

        $response = $this->put(route('permission.update', $permission->id), [
            'name' => 'permission.test2',
        ]);

        $response->assertStatus(403);
        $response->assertSee('403');
    }

    public function test_super_admin_can_update_permission(): void
    {
        $user = $this->createUserWithRoles('super admin');

        $this->actingAs($user);

        $permission = Permission::create(['name' => 'permission.test']);

        $response = $this->put(route('permission.update', $permission->id), [
            'name' => 'permission.test2',
        ]);

        $response->assertStatus(302);
        $response->assertRedirect(route('permission.index'));
        $response->assertSessionHas('toastr-success', 'Permission updated successfully');
        $this->assertDatabaseHas('permissions', [
            'name' => 'permission.test2',
        ]);
    }

    public function test_user_can_update_permission_if_authorized():void
    {
        $user = $this->createUserWithRoles('employee');
        $this->assignPermission('edit permission', $user);

        $this->actingAs($user);

        $permission = Permission::create(['name' => 'permission.test']);

        $response = $this->put(route('permission.update', $permission->id), [
            'name' => 'permission.test2',
        ]);

        $response->assertStatus(302);
        $response->assertRedirect(route('permission.index'));
        $response->assertSessionHas('toastr-success', 'Permission updated successfully');
        $this->assertDatabaseHas('permissions', [
            'name' => 'permission.test2',
        ]);
    }
}
