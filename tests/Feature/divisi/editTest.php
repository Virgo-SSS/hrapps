<?php

namespace Tests\Feature\divisi;

use App\Models\Divisi;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class editTest extends TestCase
{
    public function test_user_cant_redirect_to_edit_divisi_page_if_not_authenticated(): void
    {
        $divisi = Divisi::factory()->create();

        $response = $this->get(route('divisi.edit', $divisi));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }

    public function test_user_cant_redirect_to_edit_divisi_page_if_not_authorized(): void
    {
        $user = $this->createUserWithRoles('invalidRoles');
        $this->assignPermission('invalidPermission', $user);
        $this->actingAs($user);

        $divisi = Divisi::factory()->create();

        $response = $this->get(route('divisi.edit', $divisi));

        $response->assertStatus(403);
    }

    public function test_super_can_redirect_to_edit_divisi_page(): void
    {
        $user = $this->createUserWithRoles('super admin');
        $this->actingAs($user);

        $divisi = Divisi::factory()->create();

        $response = $this->get(route('divisi.edit', $divisi));

        $response->assertStatus(200);
        $response->assertSeeText('Edit Divisi');
    }

    public function test_user_can_redirect_to_edit_divisi_page_if_authorized(): void
    {
        $user = $this->createUserWithRoles('employee');
        $this->assignPermission('edit division', $user);
        $this->actingAs($user);

        $divisi = Divisi::factory()->create();

        $response = $this->get(route('divisi.edit', $divisi));

        $response->assertStatus(200);
        $response->assertSeeText('Edit Divisi');
    }
}
