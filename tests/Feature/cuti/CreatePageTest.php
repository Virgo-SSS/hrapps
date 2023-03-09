<?php

namespace Tests\Feature\cuti;

use App\Models\Cuti;
use App\Models\UserProfile;

class CreatePageTest extends baseCuti
{
    public function test_user_cant_redirect_to_create_cuti_page_if_not_authenticated(): void
    {
        $response = $this->get(route('cuti.create'));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }

    public function test_user_cant_redirect_to_create_cuti_page_if_not_authorized(): void
    {
        $user = $this->createUserWithRoles('invalidRoles');
        $this->assignPermission('invalidPermission', $user);

        $response = $this->actingAs($user)->get(route('cuti.create'));

        $response->assertStatus(403);
    }

    public function test_user_can_redirect_to_create_cuti_page_if_authenticated(): void
    {
        $user = $this->createUserWithRoles('super admin');
        UserProfile::factory()->create([
            'user_id' => $user->id,
        ]);

        $response = $this->actingAs($user)->get(route('cuti.create'));

        $response->assertStatus(200);
        $response->assertViewIs('cuti.create');
    }



}
