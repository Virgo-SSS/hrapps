<?php

namespace Tests\Feature;

use App\Models\Divisi;
use App\Models\Posisi;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PosisiTest extends TestCase
{
    public function test_user_cant_go_to_posisi_page_if_unauthenticated()
    {
        $response = $this->get(route('posisi.index'));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }

    public function test_user_can_go_to_posisi_page_if_authenticated()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get(route('posisi.index'));

        $response->assertStatus(200);
        $response->assertViewIs('posisi.index');
        $response->assertSeeText('Data Posisi');
    }

    public function test_user_cant_store_posisi_if_unauthenticated()
    {
        $response = $this->post(route('posisi.store'), [
            'name' => 'Posisi 1',
            'divisi_id' => 1,
        ]);

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }

    public function test_store_posisi_field_name_required()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->post(route('posisi.store'), [
            'name' => '',
            'divisi_id' => 1,
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors('name');
        $this->assertEquals('The name field is required.', session()->get('errors')->first('name'));
    }

    public function test_store_posisi_field_name_max_255()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->post(route('posisi.store'), [
            'name' => str_repeat('a', 256),
            'divisi_id' => 1,
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors('name');
        $this->assertEquals('The name must not be greater than 255 characters.', session()->get('errors')->first('name'));
    }

    public function test_store_posisi_field_name_must_be_string()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->post(route('posisi.store'), [
            'name' => 1,
            'divisi_id' => 1,
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors('name');
        $this->assertEquals('The name must be a string.', session()->get('errors')->first('name'));
    }

    public function test_store_posisi_field_divisi_required()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->post(route('posisi.store'), [
            'name' => 'Posisi 1',
            'divisi_id' => '',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors('divisi_id');
        $this->assertEquals('The divisi id field is required.', session()->get('errors')->first('divisi_id'));
    }

    public function test_store_posisi_field_divisi_exist_at_database()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->post(route('posisi.store'), [
            'name' => 'Posisi 1',
            'divisi_id' => 999999,
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors('divisi_id');
        $this->assertEquals('The selected divisi id is invalid.', session()->get('errors')->first('divisi_id'));
    }

    public function test_store_posisi_field_divisi_must_be_integer()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->post(route('posisi.store'), [
            'name' => 'Posisi 1',
            'divisi_id' => 'string',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors('divisi_id');
        $this->assertEquals('The divisi id must be an integer.', session()->get('errors')->first('divisi_id'));
    }

    public function test_user_can_store_posisi_if_authenticated()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $divisi = Divisi::factory(1)->create();
        $response = $this->post(route('posisi.store'), [
            'name' => 'Posisi 1',
            'divisi_id' => $divisi->first()->id
        ]);

        $response->assertStatus(302);
        $response->assertRedirect(route('posisi.index'));
        $response->assertSessionHas('toastr-success', 'Posisi Successfully Created');
        $this->assertDatabaseHas('posisi', [
            'name' => 'Posisi 1',
            'divisi_id' => $divisi->first()->id
        ]);
    }

    public function test_user_cant_update_posisi_if_unauthenticated()
    {
        $response = $this->put(route('posisi.update', 1), [
            'name' => 'Posisi 1',
            'divisi_id' => 1,
        ]);

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }

    public function test_update_posisi_field_name_required()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $posisi = Posisi::factory(1)->create();
        $divisi = Divisi::factory(1)->create();
        $response = $this->put(route('posisi.update', $posisi->first()->id), [
            'name' => '',
            'divisi_id' => $divisi->first()->id,
            'is_active' => true,
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors('name');
        $this->assertEquals('The name field is required.', session()->get('errors')->first('name'));
    }

    public function test_update_posisi_field_name_max_255()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $posisi = Posisi::factory(1)->create();
        $divisi = Divisi::factory(1)->create();
        $response = $this->put(route('posisi.update', $posisi->first()->id), [
            'name' => str_repeat('a', 256),
            'divisi_id' => $divisi->first()->id,
            'is_active' => true,
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors('name');
        $this->assertEquals('The name must not be greater than 255 characters.', session()->get('errors')->first('name'));
    }

    public function test_update_posisi_field_name_must_be_string()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $posisi = Posisi::factory(1)->create();
        $divisi = Divisi::factory(1)->create();
        $response = $this->put(route('posisi.update', $posisi->first()->id), [
            'name' => 1,
            'divisi_id' => $divisi->first()->id,
            'is_active' => true,
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors('name');
        $this->assertEquals('The name must be a string.', session()->get('errors')->first('name'));
    }

    public function test_update_posisi_field_divisi_required()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $posisi = Posisi::factory(1)->create();
        $response = $this->put(route('posisi.update', $posisi->first()->id), [
            'name' => 'Posisi 1',
            'divisi_id' => '',
            'is_active' => true,
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors('divisi_id');
        $this->assertEquals('The divisi id field is required.', session()->get('errors')->first('divisi_id'));
    }

    public function test_update_posisi_field_divisi_exist_at_database()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $posisi = Posisi::factory(1)->create();
        $response = $this->put(route('posisi.update', $posisi->first()->id), [
            'name' => 'Posisi 1',
            'divisi_id' => 999999,
            'is_active' => true,
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors('divisi_id');
        $this->assertEquals('The selected divisi id is invalid.', session()->get('errors')->first('divisi_id'));
    }

    public function test_update_posisi_field_divisi_must_be_integer()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $posisi = Posisi::factory(1)->create();
        $response = $this->put(route('posisi.update', $posisi->first()->id), [
            'name' => 'Posisi 1',
            'divisi_id' => 'string',
            'is_active' => true,
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors('divisi_id');
        $this->assertEquals('The divisi id must be an integer.', session()->get('errors')->first('divisi_id'));
    }

    public function test_update_posisi_field_is_active_required()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $posisi = Posisi::factory(1)->create();
        $divisi = Divisi::factory(1)->create();
        $response = $this->put(route('posisi.update', $posisi->first()->id), [
            'name' => 'Posisi 1',
            'divisi_id' => $divisi->first()->id,
            'is_active' => ''
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors('is_active');
        $this->assertEquals('The is active field is required.', session()->get('errors')->first('is_active'));
    }

    public function test_update_posisi_field_is_active_boolean()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $posisi = Posisi::factory(1)->create();
        $divisi = Divisi::factory(1)->create();
        $response = $this->put(route('posisi.update', $posisi->first()->id), [
            'name' => 'Posisi 1',
            'divisi_id' => $divisi->first()->id,
            'is_active' => 'string'
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors('is_active');
        $this->assertEquals('The is active field must be true or false.', session()->get('errors')->first('is_active'));
    }

    public function test_user_can_update_posisi_if_authenticated()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $posisi = Posisi::factory(1)->create();
        $divisi = Divisi::factory(1)->create();
        $response = $this->put(route('posisi.update', $posisi->first()->id), [
            'name' => 'Posisi 1',
            'divisi_id' => $divisi->first()->id,
            'is_active' => true,
        ]);

        $response->assertStatus(302);
        $response->assertRedirect(route('posisi.index'));
        $response->assertSessionHas('toastr-success', 'Posisi Successfully Updated');
        $this->assertDatabaseHas('posisi', [
            'name' => 'Posisi 1',
            'divisi_id' => $divisi->first()->id,
            'is_active' => true,
        ]);

    }

   public function test_user_cant_delete_posisi_if_not_authenticated()
   {
         $posisi = Posisi::factory(1)->create();
         $response = $this->delete(route('posisi.destroy', $posisi->first()->id));

         $response->assertStatus(302);
         $response->assertRedirect(route('login'));
   }

    public function test_user_can_delete_posisi_if_authenticated()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $posisi = Posisi::factory(1)->create();
        $response = $this->delete(route('posisi.destroy', $posisi->first()->id));

        $response->assertStatus(302);
        $response->assertRedirect(route('posisi.index'));
        $response->assertSessionHas('toastr-success', 'Posisi Successfully Deleted');
        $this->assertDatabaseMissing('posisi', [
            'id' => $posisi->first()->id,
        ]);
    }

    public function test_get_posisi_data_by_divisi_with_json()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $divisi = Divisi::factory(1)->create();
        $posisi = Posisi::factory(1)->create([
            'divisi_id' => $divisi->first()->id,
            'is_active' => 0 // because from database return 0 or 1 not true or false but the point is still same
        ]);

        $response = $this->json('GET', route('posisi.by-divisi', $divisi->first()->id));
        $response->assertStatus(200);
        $response->assertJson($posisi->toArray());
        $response->assertExactJson($posisi->toArray());
    }
}

