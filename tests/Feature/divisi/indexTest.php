<?php

namespace Tests\Feature\divisi;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class indexTest extends TestCase
{
    public function test_user_cant_access_divisi_page_if_user_unauthenticated(): void
    {
        $response = $this->get(route('divisi.index'));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }

    public function test_user_cant_access_divisi_pageif_user_not_authorized(): void
    {
        $user = $this->createUserWithRoles('invalidRoles');
        $this->assignPermission('invalidPermission', $user);
        $this->actingAs($user);

        $response = $this->get(route('divisi.index'));

        $response->assertStatus(403);
    }

    public function test_super_admin_can_access_divisi_page_if_authenticated(): void
    {
        $user  = $this->createUserWithRoles('super admin');
        $this->actingAs($user);

        $response = $this->get(route('divisi.index'));

        $response->assertStatus(200);
        $response->assertSeeText('Data Divisi');
    }

    public function test_user_can_access_divisi_page_if_user_authenticated(): void
    {
        $user  = $this->createUserWithRoles('employee');
        $this->assignPermission('view division', $user);
        $this->actingAs($user);

        $response = $this->get(route('divisi.index'));

        $response->assertStatus(200);
        $response->assertSeeText('Data Divisi');
    }
}
