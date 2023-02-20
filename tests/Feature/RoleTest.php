<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class RoleTest extends TestCase
{
    public function test_user_cant_view_role_page_if_not_authenticated(): void
    {
        $response = $this->get(route('role.index'));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }

    public function test_user_cant_view_role_page_if_not_authorized(): void
    {
        $user = $this->createUserWithRoles('invalidroles');
        $this->assignPermission('invalid Permission', $user);
        $this->actingAs($user);

        $response = $this->get(route('role.index'));

        $response->assertStatus(403);
    }

    public function test_super_admin_can_view_role_page(): void
    {
        $user = $this->createUserWithRoles('super admin');
        $this->actingAs($user);

        $response = $this->get(route('role.index'));

        $response->assertStatus(200);
        $response->assertViewIs('role.index');
    }

    public function test_user_can_view_page_if_authorized(): void
    {
        $user = $this->createUserWithRoles('employee');
        $this->assignPermission('view role', $user);
        $this->actingAs($user);

        $response = $this->get(route('role.index'));

        $response->assertStatus(200);
        $response->assertViewIs('role.index');
    }

    public function test_user_cant_redirect_to_create_role_if_not_authenticated(): void
    {
        $response = $this->get(route('role.create'));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }

    public function test_user_cant_redirect_to_create_role_if_not_authorized(): void
    {
        $user = $this->createUserWithRoles('invalidroles');
        $this->assignPermission('invalid Permission', $user);
        $this->actingAs($user);

        $response = $this->get(route('role.create'));

        $response->assertStatus(403);
    }

    public function test_super_admin_can_redirect_to_create_role(): void
    {
        $user = $this->createUserWithRoles('super admin');
        $this->actingAs($user);

        $response = $this->get(route('role.create'));

        $response->assertStatus(200);
        $response->assertViewIs('role.create');
    }

    public function test_user_can_redirect_to_create_role_if_authorized(): void
    {
        $user = $this->createUserWithRoles('employee');
        $this->assignPermission('create role', $user);
        $this->actingAs($user);

        $response = $this->get(route('role.create'));

        $response->assertStatus(200);
        $response->assertViewIs('role.create');
    }

    public function test_user_cant_store_role_if_not_authenticated(): void
    {
        $response = $this->post(route('role.store'));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }

    public function test_user_cant_store_role_if_not_authorized(): void
    {
        $user = $this->createUserWithRoles('invalidroles');
        $this->assignPermission('invalid Permission', $user);
        $this->actingAs($user);

        $response = $this->post(route('role.store'), [
            'name' => 'test roles',
        ]);

        $response->assertStatus(403);
    }

    public function test_store_role_field_name_is_required(): void
    {
        $user = $this->createUserWithRoles('super admin');
        $this->actingAs($user);

        $response = $this->post(route('role.store'), [
            'name' => '',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors('name');
        $this->assertEquals('The name field is required.', session()->get('errors')->first('name'));
    }

    public function test_store_role_field_name_is_unique(): void
    {
        $user = $this->createUserWithRoles('super admin');
        $this->actingAs($user);

        $response = $this->post(route('role.store'), [
            'name' => 'super admin',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors('name');
        $this->assertEquals('The name has already been taken.', session()->get('errors')->first('name'));
    }

    public function test_store_role_field_permission_must_be_array(): void
    {
        $user = $this->createUserWithRoles('super admin');
        $this->actingAs($user);

        $response = $this->post(route('role.store'), [
            'name' => 'test roles',
            'permission' => 12312313,
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors('permission');
        $this->assertEquals('The permission must be an array.', session()->get('errors')->first('permission'));
    }

    public function test_store_role_field_permission_the_key_must_exists_at_permission_id(): void
    {
        $user = $this->createUserWithRoles('super admin');
        $this->actingAs($user);

        $response = $this->post(route('role.store'), [
            'name' => 'test roles',
            'permission' => [
                1 => 'on', // format reference from input checkbox at view
            ],
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors('permission');
        $this->assertEquals('The selected permission is invalid.', session()->get('errors')->first('permission'));
    }

    public function test_super_admin_can_store_roles_with_bind_permission(): void
    {
        $user = $this->createUserWithRoles('super admin');
        $this->actingAs($user);

        $permission1 = Permission::create(['name' => 'test permission']);
        $permission2 = Permission::create(['name' => 'test permission 2']);

        $response = $this->post(route('role.store'), [
            'name' => 'test roles',
            'permission' => [
                $permission1->id => 'on', // format reference from input checkbox at view
                $permission2->id => 'on', // format reference from input checkbox at view
            ]
        ]);

        $response->assertStatus(302);
        $response->assertRedirect(route('role.index'));
        $response->assertSessionHas('toastr-success', 'Role created successfully.');
        $this->assertDatabaseHas('roles', [
            'name' => 'test roles',
        ]);

        $role = Role::where('name', 'test roles')->first();
        $this->assertDatabaseHas('role_has_permissions', [
            'permission_id' => $permission1->id,
            'role_id' => $role->id,
        ]);
        $this->assertTrue($role->hasPermissionTo($permission1->name));
        $this->assertTrue($role->hasPermissionTo($permission2->name));
    }

    public function test_super_admin_can_store_roles_without_bind_permission(): void
    {
        $user = $this->createUserWithRoles('super admin');
        $this->actingAs($user);

        $response = $this->post(route('role.store'), [
            'name' => 'test roles',
        ]);

        $response->assertStatus(302);
        $response->assertRedirect(route('role.index'));
        $response->assertSessionHas('toastr-success', 'Role created successfully.');

        $this->assertDatabaseHas('roles', [
            'name' => 'test roles',
        ]);

        $role = Role::where('name', 'test roles')->first();
        $this->assertDatabaseMissing('role_has_permissions', [
            'role_id' => $role->id,
        ]);

    }

    public function test_user_can_store_role_without_bind_permission_if_authorized(): void
    {
        $user = $this->createUserWithRoles('employee');
        $this->assignPermission('create role', $user);
        $this->actingAs($user);

        $response = $this->post(route('role.store'), [
            'name' => 'test roles',
        ]);

        $response->assertStatus(302);
        $response->assertRedirect(route('role.index'));
        $response->assertSessionHas('toastr-success', 'Role created successfully.');
        $this->assertDatabaseHas('roles', [
            'name' => 'test roles',
        ]);

        $role = Role::where('name', 'test roles')->first();
        $this->assertDatabaseMissing('role_has_permissions', [
            'role_id' => $role->id,
        ]);
    }

    public function test_user_can_store_role_with_bind_permission_if_authorized(): void
    {
        $user = $this->createUserWithRoles('employee');
        $this->assignPermission('create role', $user);
        $this->actingAs($user);

        $permission1 = Permission::create(['name' => 'test permission']);
        $permission2 = Permission::create(['name' => 'test permission 2']);

        $response = $this->post(route('role.store'), [
            'name' => 'test roles',
            'permission' =>  [
                $permission1->id => 'on', // format reference from input checkbox at view
                $permission2->id => 'on', // format reference from input checkbox at view
            ]
        ]);

        $response->assertStatus(302);
        $response->assertRedirect(route('role.index'));
        $response->assertSessionHas('toastr-success', 'Role created successfully.');
        $this->assertDatabaseHas('roles', [
            'name' => 'test roles',
        ]);

        $role = Role::with('permissions')->where('name', 'test roles')->first();

        $this->assertTrue($role->hasPermissionTo($permission1->name));
        $this->assertTrue($role->hasPermissionTo($permission2->name));
    }

    public function test_user_cant_get_permission_by_role_if_not_authenticated(): void
    {
        $response = $this->get(route('role.permissions', 1));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }

    public function test_user_cant_get_permission_by_role_if_not_authorized(): void
    {
        $user = $this->createUserWithRoles('invalidroles');
        $this->assignPermission('invalid Permission', $user);
        $this->actingAs($user);

        $role = Role::create(['name' => 'test roles']);
        $permission = Permission::create(['name' => 'test permission']);
        $role->givePermissionTo($permission);

        $response = $this->get(route('role.permissions', $role->id));

        $response->assertStatus(403);
    }

    public function test_user_can_get_permission_by_role_if_authtorized(): void
    {
        $user = $this->createUserWithRoles('employee');
        $this->assignPermission('view role', $user);
        $this->actingAs($user);

        $role = Role::create(['name' => 'test roles']);
        $permission = Permission::create(['name' => 'test permission']);
        $role->givePermissionTo($permission);

        $response = $this->get(route('role.permissions', $role->id));

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'name',
                ]
            ]
        ]);
        $response->assertJson([
            'data' => [
                [
                    'name' => $permission->name,
                ]
            ]
        ]);
    }

    public function test_user_cant_redirect_to_edit_role_if_not_authenticated(): void
    {
        $response = $this->get(route('role.edit', 1));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }

    public function test_user_cant_redirect_to_edit_role_if_not_authorized(): void
    {
        $user = $this->createUserWithRoles('invalidroles');
        $this->assignPermission('invalid Permission', $user);
        $this->actingAs($user);

        $role = Role::create(['name' => 'test roles']);

        $response = $this->get(route('role.edit', $role->id));

        $response->assertStatus(403);
    }

    public function test_super_admin_can_redirect_to_edit_role(): void
    {
        $user = $this->createUserWithRoles('super admin');
        $this->actingAs($user);

        $role = Role::create(['name' => 'test roles']);

        $response = $this->get(route('role.edit', $role->id));

        $response->assertStatus(200);
        $response->assertViewIs('role.edit');
    }

    public function test_user_can_redirect_to_edit_role_if_authorized(): void
    {
        $user = $this->createUserWithRoles('employee');
        $this->assignPermission('edit role', $user);
        $this->actingAs($user);

        $role = Role::create(['name' => 'test roles']);

        $response = $this->get(route('role.edit', $role->id));

        $response->assertStatus(200);
        $response->assertViewIs('role.edit');

    }

    public function test_user_cant_update_role_if_not_authenticated(): void
    {
        $response = $this->put(route('role.update', 1), [
            'name' => 'test roles',
        ]);

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }

    public function test_user_cant_update_role_if_not_authorized(): void
    {
        $user = $this->createUserWithRoles('invalidroles');
        $this->assignPermission('invalid Permission', $user);
        $this->actingAs($user);

        $role = Role::create(['name' => 'test roles']);

        $response = $this->put(route('role.update', $role->id), [
            'name' => 'test roles',
        ]);

        $response->assertStatus(403);
    }

    public function test_update_roles_field_name_is_required(): void
    {
        $user = $this->createUserWithRoles('employee');
        $this->assignPermission('edit role', $user);
        $this->actingAs($user);

        $role = Role::create(['name' => 'test roles']);

        $response = $this->put(route('role.update', $role->id), [
            'name' => '',
        ]);

        $response->assertSessionHasErrors('name');
        $this->assertEquals('The name field is required.', session()->get('errors')->first('name'));
    }

    public function test_update_roles_field_permission_must_be_array(): void
    {
        $user = $this->createUserWithRoles('employee');
        $this->assignPermission('edit role', $user);
        $this->actingAs($user);

        $role = Role::create(['name' => 'test roles']);

        $response = $this->put(route('role.update', $role->id), [
            'name' => 'test roles',
            'permission' => 'test permission',
        ]);

        $response->assertSessionHasErrors('permission');
        $this->assertEquals('The permission must be an array.', session()->get('errors')->first('permission'));
    }

    public function test_update_roles_field_permission_must_be_exists_in_permission_table(): void
    {
        $user = $this->createUserWithRoles('employee');
        $this->assignPermission('edit role', $user);
        $this->actingAs($user);

        $role = Role::create(['name' => 'test roles']);

        $response = $this->put(route('role.update', $role->id), [
            'name' => 'test roles',
            'permission' => [
                    8888999 => 'on', // format reference from input checkbox at view
            ],
        ]);

        $response->assertSessionHasErrors('permission');
        $this->assertEquals('The selected permission is invalid.', session()->get('errors')->first('permission'));
    }

    public function test_super_admin_can_update_roles_with_bind_permission(): void
    {
        $user = $this->createUserWithRoles('super admin');
        $this->actingAs($user);

        $role = Role::create(['name' => 'test roles']);
        $permission = Permission::create(['name' => 'test permission']);
        $role->givePermissionTo($permission);

        $permission2 = Permission::create(['name' => 'test permission2']);

        $response = $this->put(route('role.update', $role->id), [
            'name' => 'test roles123',
            'permission' => [
                $permission2->id => 'on', // format reference from input checkbox at view
            ],
        ]);

        $response->assertStatus(302);
        $response->assertRedirect(route('role.index'));
        $response->assertSessionHas('toastr-success', 'Role updated successfully.');
        $this->assertEquals('test roles123', $role->fresh()->name);
        $this->assertTrue($role->fresh()->hasPermissionTo($permission2->name));
        $this->assertFalse($role->fresh()->hasPermissionTo($permission->name));
    }

    public function test_super_admin_can_update_roles_without_bind_permission(): void
    {
        $user = $this->createUserWithRoles('super admin');
        $this->actingAs($user);

        $role = Role::create(['name' => 'test roles']);

        $response = $this->put(route('role.update', $role->id), [
            'name' => 'test roles123',
        ]);

        $response->assertStatus(302);
        $response->assertRedirect(route('role.index'));
        $response->assertSessionHas('toastr-success', 'Role updated successfully.');
        $this->assertEquals('test roles123', $role->fresh()->name);
    }

    public function test_user_can_update_roles_with_bind_permission_if_authorized(): void
    {
        $user = $this->createUserWithRoles('employee');
        $this->assignPermission('edit role', $user);
        $this->actingAs($user);

        $role = Role::create(['name' => 'test roles']);
        $permission = Permission::create(['name' => 'test permission']);
        $role->givePermissionTo($permission);

        $permission2 = Permission::create(['name' => 'test permission2']);

        $response = $this->put(route('role.update', $role->id), [
            'name' => 'test roles123',
            'permission' => [
                $permission2->id => 'on', // format reference from input checkbox at view
            ],
        ]);

        $response->assertStatus(302);
        $response->assertRedirect(route('role.index'));
        $response->assertSessionHas('toastr-success', 'Role updated successfully.');
        $this->assertEquals('test roles123', $role->fresh()->name);
        $this->assertTrue($role->fresh()->hasPermissionTo($permission2->name));
        $this->assertFalse($role->fresh()->hasPermissionTo($permission->name));
    }

    public function test_user_can_update_roles_without_bind_permission_if_authorized(): void
    {
        $user = $this->createUserWithRoles('employee');
        $this->assignPermission('edit role', $user);
        $this->actingAs($user);

        $role = Role::create(['name' => 'test roles']);

        $response = $this->put(route('role.update', $role->id), [
            'name' => 'test roles123',
        ]);

        $response->assertStatus(302);
        $response->assertRedirect(route('role.index'));
        $response->assertSessionHas('toastr-success', 'Role updated successfully.');
        $this->assertEquals('test roles123', $role->fresh()->name);
    }

    public function test_user_cant_delete_role_if_unauthenticated(): void
    {
        $role = Role::create(['name' => 'test roles']);

        $response = $this->delete(route('role.destroy', $role->id));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }

    public function test_user_cant_delete_role_if_not_authorized(): void
    {
        $user = $this->createUserWithRoles('invalidRoles');
        $this->assignPermission('invalidPermission', $user);
        $this->actingAs($user);

        $role = Role::create(['name' => 'test roles']);

        $response = $this->delete(route('role.destroy', $role->id));

        $response->assertStatus(403);
    }

    public function test_user_can_delete_role_if_authorized(): void
    {
        $user = $this->createUserWithRoles('employee');
        $this->assignPermission('delete role', $user);
        $this->actingAs($user);

        $role = Role::create(['name' => 'test roles']);

        $response = $this->delete(route('role.destroy', $role->id));

        $response->assertStatus(302);
        $response->assertRedirect(route('role.index'));
        $response->assertSessionHas('toastr-success', 'Role deleted successfully.');
        $this->assertNull($role->fresh());
    }

    public function test_super_admin_can_delete_role(): void
    {
        $user = $this->createUserWithRoles('super admin');
        $this->actingAs($user);

        $role = Role::create(['name' => 'test roles']);

        $response = $this->delete(route('role.destroy', $role->id));

        $response->assertStatus(302);
        $response->assertRedirect(route('role.index'));
        $response->assertSessionHas('toastr-success', 'Role deleted successfully.');
        $this->assertNull($role->fresh());
    }
}
