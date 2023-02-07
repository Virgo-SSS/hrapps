<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class loginTest extends TestCase
{
    public function test_can_go_to_login_page_if_user_not_authenticated(): void
    {
        $response = $this->get(route('login'));

        $response->assertStatus(200);
        $response->assertSeeText('Sign In');
    }

    public function test_can_not_go_to_login_page_if_user_authenticated(): void
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get(route('login'));

        $response->assertStatus(302);
        $response->assertRedirect(route('home'));
    }

    public function test_user_cant_login_if_password_null(): void
    {
        $user = User::factory()->create();
        $response = $this->post(route('login'), [
            'uuid' => $user->uuid,
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors('password');
    }

    public function test_user_cant_login_if_uuid_null(): void
    {
        $response = $this->post(route('login'), [
            'password' => 'password',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors('uuid');
    }

    public function test_user_cant_login_if_uuid_has_string(): void
    {
        $response = $this->post(route('login'), [
            'uuid' => 'string',
            'password' => 'password',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors('uuid');
    }

    public function test_user_cant_login_if_uuid_doesnt_exitst(): void
    {
        $response = $this->post(route('login'), [
            'uuid' => '00000000-0000-0000-0000-000000000000',
            'password' => 'password',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors('uuid');
    }

    public function test_user_cant_login_if_password_doesnt_match(): void
    {
        $user = User::factory()->create();
        $response = $this->post(route('login'), [
            'uuid' => $user->uuid,
            'password' => 'wrong_password',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors('uuid');
    }

    public function test_user_can_login_if_password_match(): void
    {
        $user = User::factory()->create();
        $response = $this->post(route('login'), [
            'uuid' => $user->uuid,
            'password' => 'password',
        ]);

        $response->assertStatus(302);
        $response->assertRedirect(route('home'));
    }
}
