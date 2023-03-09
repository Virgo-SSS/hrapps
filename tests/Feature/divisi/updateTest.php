<?php

namespace Tests\Feature\divisi;

use App\Models\Divisi;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class updateTest extends TestCase
{
    public function test_user_cant_update_divisi_if_user_unauthenticated(): void
    {
        $divisi = Divisi::factory()->create();

        $response = $this->put(route('divisi.update', $divisi), [
            'name' => 'Edit Divisi 1',
        ]);

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertDatabaseMissing('divisi', [
            'name' => 'Edit Divisi 1',
        ]);
    }

    public function test_user_cant_update_divsi_if_not_authorized(): void
    {
        $user = $this->createUserWithRoles('invalidRoles');
        $this->assignPermission('invalidPermission', $user);
        $this->actingAs($user);

        $divisi = Divisi::factory()->create();

        $response = $this->put(route('divisi.update', $divisi), [
            'name' => 'Edit Divisi 1',
            'is_active' => true,
        ]);

        $response->assertStatus(403);
        $this->assertDatabaseMissing('divisi', [
            'name' => 'Edit Divisi 1',
        ]);
    }

    public function test_update_divisi_field_name_required(): void
    {
        $user  = $this->createUserWithRoles('super admin');
        $this->actingAs($user);

        $divisi = Divisi::factory()->create();

        $response = $this->put(route('divisi.update', $divisi), [
            'name' => '',
            'is_active' => true,
        ]);

        $response->assertSessionHasErrors('name');
        $this->assertEquals('The name field is required.', session()->get('errors')->first('name'));
    }

    public function test_update_divisi_field_is_active_required(): void
    {
        $user  = $this->createUserWithRoles('super admin');
        $this->actingAs($user);

        $divisi = Divisi::factory()->create();

        $response = $this->put(route('divisi.update', $divisi), [
            'name' => $divisi->name,
            'is_active' => '',
        ]);

        $response->assertSessionHasErrors('is_active');
        $this->assertEquals('The is active field is required.', session()->get('errors')->first('is_active'));
    }

    public function test_update_divisi_field_is_active_must_boolean(): void
    {
        $user  = $this->createUserWithRoles('super admin');
        $this->actingAs($user);

        $divisi = Divisi::factory()->create();

        $response = $this->put(route('divisi.update', $divisi), [
            'name' => $divisi->name,
            'is_active' => 'test',
        ]);

        $response->assertSessionHasErrors('is_active');
        $this->assertEquals('The is active field must be true or false.', session()->get('errors')->first('is_active'));
    }

    public function test_update_divisi_field_name_cant_has_duplicate_name(): void
    {
        $user  = $this->createUserWithRoles('super admin');
        $this->actingAs($user);

        $divisi1 = Divisi::factory()->create();
        $divisi2 = Divisi::factory()->create();

        $response = $this->put(route('divisi.update', $divisi2), [
            'name' => $divisi1->name,
            'is_active' => true,
        ]);

        $response->assertSessionHasErrors('name');
        $this->assertEquals('The name has already been taken.', session()->get('errors')->first('name'));
    }

    public function test_update_divisi_field_name_cant_more_than_255(): void
    {
        $user = $this->createUserWithRoles('super admin');
        $this->actingAs($user);

        $divisi = Divisi::factory()->create();
        $response = $this->put(route('divisi.update', $divisi), [
            'name' => str_repeat('a', 256),
            'is_active' => true,
        ]);

        $response->assertSessionHasErrors('name');
        $this->assertEquals('The name must not be greater than 255 characters.', session()->get('errors')->first('name'));
    }

    public function test_update_divisi_field_name_should_be_string(): void
    {
        $user = $this->createUserWithRoles('super admin');
        $this->actingAs($user);

        $divisi = Divisi::factory()->create();
        $response = $this->put(route('divisi.update', $divisi), [
            'name' => 123,
        ]);

        $response->assertSessionHasErrors('name');
        $this->assertEquals('The name must be a string.', session()->get('errors')->first('name'));
    }

    public function test_user_cant_update_divisi_if_user_unauthorized(): void
    {
        $user  = $this->createUserWithRoles('invalidRoles');
        $this->assignPermission('invalidPermission', $user);
        $this->actingAs($user);

        $divisi = Divisi::factory()->create();

        $response = $this->put(route('divisi.update', $divisi), [
            'name' => 'Edit Divisi 1',
            'is_active' => $divisi->is_active,
        ]);

        $response->assertStatus(403);
        $this->assertDatabaseMissing('divisi', [
            'name' => 'Edit Divisi 1',
        ]);
    }

    public function test_super_admin_can_update_name_divisi_if_user_authenticated(): void
    {
        $user = $this->createUserWithRoles('super admin');
        $this->actingAs($user);

        $divisi = Divisi::factory()->create();
        $response = $this->put(route('divisi.update', $divisi), [
            'name' => 'Edit Divisi 1',
            'is_active' => $divisi->is_active,
        ]);

        $response->assertStatus(302);
        $response->assertRedirect(route('divisi.index'));
        $response->assertSessionHas('toastr-success', 'Divisi Successfully Updated');
        $this->assertDatabaseHas('divisi', [
            'name' => 'Edit Divisi 1',
            'edited_by' => $user->id,
        ]);
    }

    public function test_super_admin_can_update_is_active_if_user_authenticated(): void
    {
        $user = $this->createUserWithRoles('super admin');
        $this->actingAs($user);

        $divisi = Divisi::factory()->create();
        $response = $this->put(route('divisi.update', $divisi), [
            'name' => $divisi->name,
            'is_active' => false,
        ]);

        $response->assertStatus(302);
        $response->assertRedirect(route('divisi.index'));
        $response->assertSessionHas('toastr-success', 'Divisi Successfully Updated');
        $this->assertDatabaseHas('divisi', [
            'name' => $divisi->name,
            'is_active' => false,
            'edited_by' => $user->id,
        ]);
    }

    public function test_user_can_update_name_divisi_if_user_authorizec(): void
    {
        $user = $this->createUserWithRoles('employee');
        $this->assignPermission('edit division', $user);
        $this->actingAs($user);

        $divisi = Divisi::factory()->create();
        $response = $this->put(route('divisi.update', $divisi), [
            'name' => 'Edit Divisi 1',
            'is_active' => $divisi->is_active,
        ]);

        $response->assertStatus(302);
        $response->assertRedirect(route('divisi.index'));
        $response->assertSessionHas('toastr-success', 'Divisi Successfully Updated');
        $this->assertDatabaseHas('divisi', [
            'name' => 'Edit Divisi 1',
            'edited_by' => $user->id,
        ]);
    }

    public function test_user_can_update_is_active_if_user_authorizec(): void
    {
        $user = $this->createUserWithRoles('employee');
        $this->assignPermission('edit division', $user);
        $this->actingAs($user);

        $divisi = Divisi::factory()->create();
        $response = $this->put(route('divisi.update', $divisi), [
            'name' => $divisi->name,
            'is_active' => false,
        ]);

        $response->assertStatus(302);
        $response->assertRedirect(route('divisi.index'));
        $response->assertSessionHas('toastr-success', 'Divisi Successfully Updated');
        $this->assertDatabaseHas('divisi', [
            'name' => $divisi->name,
            'is_active' => false,
            'edited_by' => $user->id,
        ]);
    }
}
