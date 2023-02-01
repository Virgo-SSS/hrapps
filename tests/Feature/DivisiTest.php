<?php

namespace Tests\Feature;

use App\Models\Divisi;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DivisiTest extends TestCase
{
    public function test_user_cant_access_divisi_page_if_user_unauthenticated()
    {
        $response = $this->get(route('divisi.index'));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }

    public function test_user_can_access_divisi_page_if_user_authenticated()
    {
        $user  = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get(route('divisi.index'));

        $response->assertStatus(200);
        $response->assertSeeText('Data Divisi');
    }

    public function test_user_cant_create_divisi_if_user_unauthenticated()
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

    public function test_store_divisi_field_name_required()
    {
        $user  = User::factory()->create();
        $this->actingAs($user);

        $response = $this->post(route('divisi.store'), [
            'name' => '',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors('name');
        $this->assertDatabaseMissing('divisi', [
            'name' => '',
        ]);
    }

    public function test_store_divisi_field_name_cant_has_duplicate_name()
    {
        $user  = User::factory()->create();
        $this->actingAs($user);

        $divisi = Divisi::factory()->create();

        $response = $this->post(route('divisi.store'), [
            'name' => $divisi->name,
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors('name');
    }

    public function test_store_divisi_field_name_cant_more_than_255()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->post(route('divisi.store'), [
            'name' => str_repeat('a', 256),
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors('name');
    }

    public function test_store_divisi_field_name_should_be_string()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->post(route('divisi.store'), [
            'name' => 123,
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors('name');
    }

    public function test_user_can_create_divisi_if_user_authenticated()
    {
        $user  = User::factory()->create();
        $this->actingAs($user);

        $response = $this->post(route('divisi.store'), [
            'name' => 'Divisi 1',
        ]);

        $response->assertStatus(302);
        $this->assertDatabaseHas('divisi', [
            'name' => 'divisi 1'
        ]);
    }

    public function test_user_cant_update_divisi_if_user_unauthenticated()
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

    public function test_update_divisi_field_name_required()
    {
        $user  = User::factory()->create();
        $this->actingAs($user);

        $divisi = Divisi::factory()->create();

        $response = $this->put(route('divisi.update', $divisi), [
            'name' => '',
            'is_active' => true,
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors('name');
    }

    public function test_update_divisi_field_is_active_required()
    {
        $user  = User::factory()->create();
        $this->actingAs($user);

        $divisi = Divisi::factory()->create();

        $response = $this->put(route('divisi.update', $divisi), [
            'name' => $divisi->name,
            'is_active' => '',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors('is_active');
    }

    public function test_update_divisi_field_is_active_must_boolean()
    {
        $user  = User::factory()->create();
        $this->actingAs($user);

        $divisi = Divisi::factory()->create();

        $response = $this->put(route('divisi.update', $divisi), [
            'name' => $divisi->name,
            'is_active' => 'test',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors('is_active');
    }

    public function test_update_divisi_field_name_cant_has_duplicate_name()
    {
        $user  = User::factory()->create();
        $this->actingAs($user);

        $divisi1 = Divisi::factory()->create();
        $divisi2 = Divisi::factory()->create();

        $response = $this->put(route('divisi.update', $divisi2), [
            'name' => $divisi1->name,
            'is_active' => true,
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors('name');
    }

    public function test_update_divisi_field_name_cant_more_than_255()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $divisi = Divisi::factory()->create();
        $response = $this->put(route('divisi.update', $divisi), [
            'name' => str_repeat('a', 256),
            'is_active' => true,
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors('name');
    }

    public function test_update_divisi_field_name_should_be_string()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $divisi = Divisi::factory()->create();
        $response = $this->put(route('divisi.update', $divisi), [
            'name' => 123,
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors('name');
    }

    public function test_user_can_update_name_divisi_if_user_authenticated()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $divisi = Divisi::factory()->create();
        $response = $this->put(route('divisi.update', $divisi), [
            'name' => 'Edit Divisi 1',
            'is_active' => $divisi->is_active,
        ]);

        $response->assertStatus(302);
        $this->assertDatabaseHas('divisi', [
            'name' => 'Edit Divisi 1',
            'edited_by' => $user->id,
        ]);
    }

    public function test_user_can_update_is_active_if_user_authenticated()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $divisi = Divisi::factory()->create();
        $response = $this->put(route('divisi.update', $divisi), [
            'name' => $divisi->name,
            'is_active' => false,
        ]);

        $response->assertStatus(302);
        $this->assertDatabaseHas('divisi', [
            'name' => $divisi->name,
            'is_active' => false,
            'edited_by' => $user->id,
        ]);
    }

    public function test_user_cant_delete_divisi_if_user_unauthenticated()
    {
        $divisi = Divisi::factory()->create();

        $response = $this->delete(route('divisi.destroy', $divisi));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertDatabaseHas('divisi', [
            'id' => $divisi->id,
        ]);
    }

    public function test_user_can_delete_divisi_if_user_authenticated()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $divisi = Divisi::factory()->create();

        $response = $this->delete(route('divisi.destroy', $divisi));

        $response->assertStatus(302);
        $this->assertDatabaseMissing('divisi', [
            'id' => $divisi->id,
        ]);
    }
}
