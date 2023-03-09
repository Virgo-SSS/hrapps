<?php

namespace Tests\Feature\divisi;

use App\Models\Divisi;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class createTest extends TestCase
{
    public function test_user_cant_create_divisi_if_user_unauthenticated(): void
    {
        $response = $this->post(route('divisi.store'), [
            'nama' => 'Divisi 1',
        ]);

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertDatabaseMissing('divisi', [
            'name' => 'Divisi 1',
        ]);
    }

    public function test_store_divisi_field_name_required(): void
    {
        $user  = $this->createUserWithRoles('super admin');
        $this->actingAs($user);

        $response = $this->post(route('divisi.store'), [
            'name' => '',
        ]);

        $response->assertSessionHasErrors('name');
        $this->assertEquals('The name field is required.', session()->get('errors')->first('name'));
    }

    public function test_store_divisi_field_name_cant_has_duplicate_name(): void
    {
        $user  = $this->createUserWithRoles('super admin');
        $this->actingAs($user);

        $divisi = Divisi::factory()->create();

        $response = $this->post(route('divisi.store'), [
            'name' => $divisi->name,
        ]);

        $response->assertSessionHasErrors('name');
        $this->assertEquals('The name has already been taken.', session()->get('errors')->first('name'));
    }

    public function test_store_divisi_field_name_cant_more_than_255(): void
    {
        $user = $this->createUserWithRoles('super admin');
        $this->actingAs($user);

        $response = $this->post(route('divisi.store'), [
            'name' => str_repeat('a', 256),
        ]);

        $response->assertSessionHasErrors('name');
        $this->assertEquals('The name must not be greater than 255 characters.', session()->get('errors')->first('name'));
    }

    public function test_store_divisi_field_name_should_be_string(): void
    {
        $user = $this->createUserWithRoles('super admin');
        $this->actingAs($user);

        $response = $this->post(route('divisi.store'), [
            'name' => 123,
        ]);

        $response->assertSessionHasErrors('name');
        $this->assertEquals('The name must be a string.', session()->get('errors')->first('name'));
    }

    public function test_user_cant_create_divisi_if_user_not_authorized(): void
    {
        $user = $this->createUserWithRoles('invalidRoles');
        $this->assignPermission('invalidPermission', $user);
        $this->actingAs($user);

        $response = $this->post(route('divisi.store'), [
            'name' => 'Divisi 1',
        ]);

        $response->assertStatus(403);
        $this->assertDatabaseMissing('divisi', [
            'name' => 'Divisi 1',
        ]);
    }

    public function test_super_admin_can_create_divisi_if_authenticated(): void
    {
        $user  = $this->createUserWithRoles('super admin');
        $this->actingAs($user);

        $response = $this->post(route('divisi.store'), [
            'name' => 'Divisi 1',
        ]);

        $response->assertStatus(302);
        $response->assertRedirect(route('divisi.index'));
        $response->assertSessionHas('toastr-success', 'Divisi Successfully Created');
        $this->assertDatabaseHas('divisi', [
            'name' => 'divisi 1'
        ]);
    }

    public function test_user_can_create_divisi_if_authorized(): void
    {
        $user  = $this->createUserWithRoles('employee');
        $this->assignPermission('create division', $user);
        $this->actingAs($user);

        $response = $this->post(route('divisi.store'), [
            'name' => 'Divisi 1',
        ]);

        $response->assertStatus(302);
        $response->assertRedirect(route('divisi.index'));
        $response->assertSessionHas('toastr-success', 'Divisi Successfully Created');
        $this->assertDatabaseHas('divisi', [
            'name' => 'divisi 1'
        ]);
    }
}
