<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CutiTest extends TestCase
{
    public function test_user_cant_access_cuti_page_if_not_authenticated(): void
    {
        $response = $this->get(route('cuti.index'));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }

    public function test_user_can_access_cuti_page_if_authenticated(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('cuti.index'));

        $response->assertStatus(200);
        $response->assertViewIs('cuti.index');
    }

    public function test_user_cant_access_create_cuti_page_if_not_authenticated(): void
    {
        $response = $this->get(route('cuti.create'));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }

    public function test_user_can_access_create_cuti_page_if_authenticated(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('cuti.create'));

        $response->assertStatus(200);
        $response->assertViewIs('cuti.create');
    }
}
