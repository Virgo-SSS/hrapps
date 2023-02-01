<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserTest extends TestCase
{
    public function test_user_cant_go_to_user_page_if_not_authenticated()
    {
        $response = $this->get(route('users.index'));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }

    public function test_user_can_go_to_user_page_if_authenticated()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get(route('users.index'));

        $response->assertStatus(200);
        $response->assertSeeText('Data Users');
    }

    public function test_user_cant_goto_user_create_page_if_not_authenticated()
    {
        $response = $this->get(route('users.create'));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }

    public function test_user_can_go_to_user_create_page_if_authenticated()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get(route('users.create'));

        $response->assertStatus(200);
        $response->assertSeeText('Create User');
    }
}
