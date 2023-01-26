<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class HomeTest extends TestCase
{
    public function test_can_go_to_home_page_if_user_authenticated()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get(route('home'));

        $response->assertStatus(200);
        $response->assertSeeText('HELLO '.strtoupper($user->name));
    }

    public function test_can_not_go_to_home_page_if_user_not_authenticated()
    {
        $response = $this->get(route('home'));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }
}
