<?php

namespace Tests\Feature\cuti;

class IndexTest extends baseCuti
{
    public function test_user_cant_access_cuti_page_if_not_authenticated(): void
    {
        $response = $this->get(route('cuti.index'));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }

    public function test_user_cant_access_cuti_page_if_unauthorized(): void
    {
        $user = $this->createUserWithRoles('invalidRoles');
        $this->assignPermission('invalidpermission', $user);

        $response = $this->actingAs($user)->get(route('cuti.index'));

        $response->assertStatus(403);
    }

    public function test_super_admin_can_access_cuti_page_if_authenticated(): void
    {
        $user = $this->createUserWithRoles('super admin');

        $response = $this->actingAs($user)->get(route('cuti.index'));

        $response->assertStatus(200);
        $response->assertViewIs('cuti.index');
        $response->assertViewHas('cutis');
    }

    public function test_user_can_access_cuti_page_if_authorized(): void
    {
        $user = $this->createUserWithRoles('employee');
        $this->assignPermission('view cuti', $user);

        $response = $this->actingAs($user)->get(route('cuti.index'));

        $response->assertStatus(200);
        $response->assertViewIs('cuti.index');
        $response->assertViewHas('cutis');
    }
}
