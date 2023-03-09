<?php

namespace Tests\Feature\permission;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;

class storeTest extends TestCase
{
    public function test_user_cant_store_permission_if_not_authenticated(): void
    {
        $response = $this->get(route('permission.store'));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }

    public function test_user_cant_store_permission_if_not_authorized(): void
    {
        $user = $this->createUserWithRoles('invalidroles');
        $this->assignPermission('invalidPermission', $user);
        $this->actingAs($user);

        $response = $this->post(route('permission.store'), [
            'name' => 'permission.test',
        ]);

        $response->assertStatus(403);
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

    public function test_user_can_store_pemission_if_authorized(): void
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
}
