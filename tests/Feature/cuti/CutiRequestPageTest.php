<?php

namespace Tests\Feature\cuti;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CutiRequestPageTest extends baseCuti
{
    public function test_user_cant_access_request_cuti_page_if_not_authenticated(): void
    {
        $response = $this->get(route('cuti.request'));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }

    public function test_user_cant_access_request_cuti_page_if_unauthorized(): void
    {
        $user = $this->createUserWithRoles('invalidRoles');
        $this->assignPermission('invalidpermission', $user);

        $response = $this->actingAs($user)->get(route('cuti.request'));

        $response->assertStatus(403);
    }

    public function test_super_admin_can_access_request_cuti_page_if_autheticated(): void
    {
        $user = $this->createUserWithRoles('super admin');

        $response = $this->actingAs($user)->get(route('cuti.request'));

        $response->assertStatus(200);
        $response->assertViewIs('cuti.request');
    }

    public function test_user_can_access_request_cuti_page_if_authorized(): void
    {
        $user = $this->createUserWithRoles('employee');
        $this->assignPermission('view cuti request', $user);

        $response = $this->actingAs($user)->get(route('cuti.request'));

        $response->assertStatus(200);
        $response->assertViewIs('cuti.request');
    }
}
