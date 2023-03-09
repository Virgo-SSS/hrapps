<?php

namespace Tests\Feature\permission;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class indexTest extends TestCase
{
    public function test_user_cant_go_to_permission_page_if_not_authenticated(): void
    {
        $response = $this->get(route('permission.index'));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }

    public function test_super_admin_can_go_to_permission_page(): void
    {
        $user = $this->createUserWithRoles('super admin');

        $this->actingAs($user);

        $response = $this->get(route('permission.index'));

        $response->assertStatus(200);
        $response->assertViewIs('role.permission');
    }

    public function test_user_cant_go_to_permission_page_if_not_authorized(): void
    {
        $user = $this->createUserWithRoles('invalidroles');
        $this->assignPermission('invalidPermission', $user);
        $this->actingAs($user);

        $response = $this->get(route('permission.index'));

        $response->assertStatus(403);
        $response->assertSee('403');
    }

    public function test_user_can_go_to_permission_page_if_authorized(): void
    {
        $user = $this->createUserWithRoles('employee');
        $this->assignPermission('view permission', $user);

        $this->actingAs($user);

        $response = $this->get(route('permission.index'));

        $response->assertStatus(200);
        $response->assertViewIs('role.permission');
    }
}
