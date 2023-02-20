<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class PermissionTest extends TestCase
{
    public function test_user_cant_go_to_permission_page_if_not_authenticated(): void
    {
        $response = $this->get(route('permission.index'));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }

    public function test_super_admin_can_go_to_permission_page_and_authenticated(): void
    {
        $user = $this->createUserWithRoles('super admin');

        $this->actingAs($user);

        $response = $this->get(route('permission.index'));

        $response->assertStatus(200);
        $response->assertViewIs('role.permission');
    }

    public function test_user_cant_go_to_permission_page_if_not_have_permission(): void
    {
        $user = $this->createUserWithRoles('invalidroles');
        $this->assignPermission('invalidPermission', $user);
        $this->actingAs($user);

        $response = $this->get(route('permission.index'));

        $response->assertStatus(403);
        $response->assertSee('403');
    }

    public function test_user_can_go_to_permission_page_if_have_permission(): void
    {
        $user = $this->createUserWithRoles('employee');
        $this->assignPermission('view permission', $user);

        $this->actingAs($user);

        $response = $this->get(route('permission.index'));

        $response->assertStatus(200);
        $response->assertViewIs('role.permission');
    }

    public function test_user_cant_store_permission_if_not_authenticated(): void
    {
        $response = $this->get(route('permission.store'));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }

    public function test_store_permission_field_name_is_required(): void
    {
        $user = $this->createUserWithRoles('super admin');
        $this->actingAs($user);

        $response = $this->post(route('permission.store'), [
            'name' => '',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors('name');
    }

    public function test_store_permission_field_name_must_be_unique(): void
    {
        $user = $this->createUserWithRoles('super admin');
        $this->actingAs($user);

        Permission::create(['name' => 'permission.test']);

        $response = $this->post(route('permission.store'), [
            'name' => 'permission.test',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors('name');
    }

    public function test_super_admin_can_store_permission(): void
    {
        $user = $this->createUserWithRoles('super admin');

        $this->actingAs($user);

        $response = $this->post(route('permission.store'), [
            'name' => 'permission.test',
        ]);

        $response->assertStatus(302);
        $response->assertRedirect(route('permission.index'));
        $response->assertSessionHas('toastr-success', 'Permission created successfully');
    }

    public function test_user_cant_store_permission_if_not_have_permission(): void
    {
        $user = $this->createUserWithRoles('invalidroles');
        $this->assignPermission('invalidPermission', $user);
        $this->actingAs($user);

        $response = $this->post(route('permission.store'), [
            'name' => 'permission.test',
        ]);

        $response->assertStatus(403);
        $response->assertSee('403');
    }

    public function test_user_can_store_pemission_if_have_permission(): void
    {
        $user = $this->createUserWithRoles('employee');
        $this->assignPermission('create permission', $user);

        $this->actingAs($user);

        $response = $this->post(route('permission.store'), [
            'name' => 'permission.test',
        ]);

        $response->assertStatus(302);
        $response->assertRedirect(route('permission.index'));
        $response->assertSessionHas('toastr-success', 'Permission created successfully');
    }

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

    public function test_user_can_update_permission_if_have_permission():void
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

    public function test_user_cant_delete_permission_if_not_authenticated(): void
    {
        $response = $this->delete(route('permission.destroy', 1));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }

    public function test_user_cant_delete_permission_without_permission(): void
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

    public function test_user_can_delete_permission_if_have_permission(): void
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
