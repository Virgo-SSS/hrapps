<?php

namespace Tests\Feature\permission;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;

class deleteTest extends TestCase
{
    public function test_user_cant_delete_permission_if_not_authenticated(): void
    {
        $response = $this->delete(route('permission.destroy', 1));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }

    public function test_user_cant_delete_permission_if_not_authorized(): void
    {
        $user = $this->createUserWithRoles('invalidroles');
        $this->assignPermission('invalidPermission', $user);
        $this->actingAs($user);

        $permission = Permission::create(['name' => 'permission.test']);

        $response = $this->delete(route('permission.destroy', $permission->id));

        $response->assertStatus(403);
        $response->assertSee('403');
    }

    public function test_super_admin_can_delete_permission(): void
    {
        $user = $this->createUserWithRoles('super admin');

        $this->actingAs($user);

        $permission = Permission::create(['name' => 'permission.test']);

        $response = $this->delete(route('permission.destroy', $permission->id));

        $response->assertStatus(302);
        $response->assertRedirect(route('permission.index'));
        $response->assertSessionHas('toastr-success', 'Permission deleted successfully');
        $this->assertDatabaseMissing('permissions', [
            'name' => 'permission.test',
        ]);
    }

    public function test_user_can_delete_permission_if_authorized(): void
    {
        $user = $this->createUserWithRoles('employee');
        $this->assignPermission('delete permission', $user);

        $this->actingAs($user);

        $permission = Permission::create(['name' => 'permission.test']);

        $response = $this->delete(route('permission.destroy', $permission->id));

        $response->assertStatus(302);
        $response->assertRedirect(route('permission.index'));
        $response->assertSessionHas('toastr-success', 'Permission deleted successfully');
        $this->assertDatabaseMissing('permissions', [
            'name' => 'permission.test',
        ]);
    }
}
