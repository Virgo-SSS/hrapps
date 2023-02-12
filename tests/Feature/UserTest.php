<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserTest extends TestCase
{
    private function prepareRequest(): array
    {
        $user = $this->makeUserArray();
        $userProfile = UserProfile::factory()->make()->toArray();

        $request = array_merge($user, $userProfile);

        unset($request['email_verified_at']);
        unset($request['user_id']);

        return $request;
    }

    public function test_user_cant_go_to_user_page_if_not_authenticated(): void
    {
        $response = $this->get(route('users.index'));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }

    public function test_user_can_go_to_user_page_if_authenticated(): void
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get(route('users.index'));

        $response->assertStatus(200);
        $response->assertSeeText('Data Employee');
        $response->assertViewIs('users.index');
    }

    public function test_user_cant_goto_user_create_page_if_not_authenticated(): void
    {
        $response = $this->get(route('users.create'));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }

    public function test_user_can_go_to_user_create_page_if_authenticated(): void
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get(route('users.create'));

        $response->assertStatus(200);
        $response->assertSeeText('Create Employee');
        $response->assertViewIs('users.create');
    }

    public function test_store_user_field_uuid_is_required(): void
    {
        $user = User::factory()->create();

        $request = $this->prepareRequest();
        $request['uuid'] = null;

        $response = $this->actingAs($user)->post(route('users.store'), $request);

        $response->assertSessionHasErrors('uuid');
        $this->assertEquals('The uuid field is required.', session()->get('errors')->first('uuid'));
    }

    public function test_store_user_field_uuid_is_unique(): void
    {
        $user = User::factory()->create();

        $request = $this->prepareRequest();
        $request['uuid'] = $user->uuid;

        $response = $this->actingAs($user)->post(route('users.store'), $request);

        $response->assertSessionHasErrors('uuid');
        $this->assertEquals('The uuid has already been taken.', session()->get('errors')->first('uuid'));
    }

    public function test_store_user_field_name_is_required(): void
    {
        $user = User::factory()->create();
        $request = $this->prepareRequest();
        $request['name'] = null;

        $response = $this->actingAs($user)->post(route('users.store'), $request);

        $response->assertSessionHasErrors('name');
        $this->assertEquals('The name field is required.', session()->get('errors')->first('name'));

    }

    public function test_store_user_field_name_must_be_string(): void
    {
        $user = User::factory()->create();
        $request = $this->prepareRequest();;
        $request['name'] = 123;

        $response = $this->actingAs($user)->post(route('users.store'), $request);

        $response->assertSessionHasErrors('name');
        $this->assertEquals('The name must be a string.', session()->get('errors')->first('name'));
    }

    public function test_store_user_field_name_max_255(): void
    {
        $user = User::factory()->create();

        $request = $this->prepareRequest();;
        $request['name'] = str_repeat('a', 256);

        $response = $this->actingAs($user)->post(route('users.store'), $request);

        $response->assertSessionHasErrors('name');
        $this->assertEquals('The name must not be greater than 255 characters.', session()->get('errors')->first('name'));
    }

    public function test_store_user_field_password_is_required(): void
    {
        $user = User::factory()->create();

        $request = $this->prepareRequest();
        $request['password'] = null;

        $response = $this->actingAs($user)->post(route('users.store'), $request);

        $response->assertSessionHasErrors('password');
        $this->assertEquals('The password field is required.', session()->get('errors')->first('password'));
    }

    public function test_store_user_field_password_min_6(): void
    {
        $user = User::factory()->create();

        $request = $this->prepareRequest();
        $reqeust['password'] = '12345';

        $response = $this->actingAs($user)->post(route('users.store'), $reqeust);

        $response->assertSessionHasErrors('password');
        $this->assertEquals('The password must be at least 6 characters.', session()->get('errors')->first('password'));
    }

    public function test_store_user_field_password_must_string(): void
    {
        $user = User::factory()->create();
        $request = $this->prepareRequest();
        $request['password'] = 123;

        $response = $this->actingAs($user)->post(route('users.store'), $request);

        $response->assertSessionHasErrors('password');
        $this->assertEquals('The password must be a string.', session()->get('errors')->first('password'));
    }

    public function test_store_user_field_email_is_required(): void
    {
        $user = User::factory()->create();
        $request = $this->prepareRequest();
        $request['email'] = null;

        $response = $this->actingAs($user)->post(route('users.store'), $request);

        $response->assertSessionHasErrors('email');
        $this->assertEquals('The email field is required.', session()->get('errors')->first('email'));
    }

    public function test_store_profile_field_bank_is_required(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $request = $this->prepareRequest();
        $request['bank'] = null;

        $response = $this->post(route('users.store'), $request);

        $response->assertSessionHasErrors('bank');
        $this->assertEquals('The bank field is required.', session()->get('errors')->first('bank'));
    }

    public function test_store_profile_field_bank_should_be_exists_in_config_bank(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $request = $this->prepareRequest();
        $request['bank'] = 999999;

        $response = $this->post(route('users.store'), $request);
        $response->assertSessionHasErrors('bank');
        $this->assertEquals('The bank must be a valid bank.', session()->get('errors')->first('bank'));
    }

    public function test_store_profile_field_bank_account_number_is_required(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $request = $this->prepareRequest();
        $request['bank_account_number'] = null;

        $response = $this->post(route('users.store'), $request);

        $response->assertSessionHasErrors('bank_account_number');
        $this->assertEquals('The bank account number field is required.', session()->get('errors')->first('bank_account_number'));
    }

    public function test_store_profile_field_bank_account_number_should_be_numeric(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        // set password field is visible
        $request = $this->prepareRequest();
        $request['bank_account_number'] = 'asdadasd';

        $response = $this->post(route('users.store'), $request);
        $response->assertSessionHasErrors('bank_account_number');
        $this->assertEquals('The bank account number must be a number.', session()->get('errors')->first('bank_account_number'));
    }

    public function test_store_profile_field_divisi_id_is_required(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $request = $this->prepareRequest();
        $request['divisi_id'] = null;

        $response = $this->post(route('users.store'), $request);

        $response->assertSessionHasErrors('divisi_id');
        $this->assertEquals('The divisi id field is required.', session()->get('errors')->first('divisi_id'));
    }

    public function test_store_profile_field_divisi_id_is_integer(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $request = $this->prepareRequest();
        $request['divisi_id'] = 'asdadasd';

        $response = $this->post(route('users.store'), $request);

        $response->assertSessionHasErrors('divisi_id');
        $this->assertEquals('The divisi id must be an integer.', session()->get('errors')->first('divisi_id'));
    }

    public function test_store_profile_field_divisi_id_should_be_exists_in_divisi_table(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $request = $this->prepareRequest();
        $request['divisi_id'] = 999999;

        $response = $this->post(route('users.store'), $request);

        $response->assertSessionHasErrors('divisi_id');
        $this->assertEquals('The selected divisi id is invalid.', session()->get('errors')->first('divisi_id'));
    }

    public function test_store_profile_field_posisi_id_is_required(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $request = $this->prepareRequest();
        $request['posisi_id'] = null;

        $response = $this->post(route('users.store'), $request);

        $response->assertSessionHasErrors('posisi_id');
        $this->assertEquals('The posisi id field is required.', session()->get('errors')->first('posisi_id'));
    }

    public function test_store_profile_field_posisi_id_is_integer(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $request = $this->prepareRequest();
        $request['posisi_id'] = 'asdadasd';

        $response = $this->post(route('users.store'), $request);

        $response->assertSessionHasErrors('posisi_id');
        $this->assertEquals('The posisi id must be an integer.', session()->get('errors')->first('posisi_id'));
    }

    public function test_store_profile_field_posisi_id_should_be_exists_in_posisi_table(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $request = $this->prepareRequest();
        $request['posisi_id'] = 999999;

        $response = $this->post(route('users.store'), $request);

        $response->assertSessionHasErrors('posisi_id');
        $this->assertEquals('The selected posisi id is invalid.', session()->get('errors')->first('posisi_id'));
    }

    public function test_store_profile_field_join_date_is_required(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $request = $this->prepareRequest();
        $request['join_date'] = null;

        $response = $this->post(route('users.store'), $request);

        $response->assertSessionHasErrors('join_date');
        $this->assertEquals('The join date field is required.', session()->get('errors')->first('join_date'));
    }

    public function test_store_profile_field_join_date_is_date(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $request = $this->prepareRequest();
        $request['join_date'] = 'asdadasd';

        $response = $this->post(route('users.store'), $request);

        $response->assertSessionHasErrors('join_date');
        $this->assertEquals('The join date is not a valid date.', session()->get('errors')->first('join_date'));
    }

    public function test_store_profile_field_cuti_is_required(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $request = $this->prepareRequest();
        $request['cuti'] = null;

        $response = $this->post(route('users.store'), $request);

        $response->assertSessionHasErrors('cuti');
        $this->assertEquals('The cuti field is required.', session()->get('errors')->first('cuti'));
    }

    public function test_store_profile_field_cuti_is_integer(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $request = $this->prepareRequest();
        $request['cuti'] = 'asdadasd';

        $response = $this->post(route('users.store'), $request);

        $response->assertSessionHasErrors('cuti');
        $this->assertEquals('The cuti must be an integer.', session()->get('errors')->first('cuti'));
    }

    public function test_store_profile_field_salary_is_required(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $request = $this->prepareRequest();
        $request['salary'] = null;

        $response = $this->post(route('users.store'), $request);

        $response->assertSessionHasErrors('salary');
        $this->assertEquals('The salary field is required.', session()->get('errors')->first('salary'));
    }

    public function test_store_profile_field_salary_is_integer(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $request = $this->prepareRequest();
        $request['salary'] = 'asdadasd';

        $response = $this->post(route('users.store'), $request);

        $response->assertSessionHasErrors('salary');
        $this->assertEquals('The salary must be a number.', session()->get('errors')->first('salary'));
    }

    public function test_user_can_store_user_with_profile(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $request = $this->prepareRequest();
        $request['uuid'] = 12345090909; // unique

        $response = $this->post(route('users.store'), $request);

        $response->assertStatus(302);
        $response->assertRedirect(route('users.index'));
        $response->assertSessionHas('toastr-success', 'User Successfully Added');

        $this->assertDatabaseHas('users', [
            'uuid' => $request['uuid'],
            'name' => $request['name'],
            'email' => $request['email'],
        ]);
        $this->assertDatabaseHas('user_profile', [
            'user_id' => User::where('uuid', $request['uuid'])->first()->id,
            'divisi_id' => $request['divisi_id'],
            'posisi_id' => $request['posisi_id'],
            'bank' => $request['bank'],
            'bank_account_number' => $request['bank_account_number'],
            'join_date' => $request['join_date'],
            'cuti' => $request['cuti'],
            'salary' => $request['salary'],
        ]);
    }

    public function test_cant_update_user_if_not_authenticated(): void
    {
        $user = User::factory()->create();

        $request = $this->prepareRequest();
        $request['uuid'] = 12345090909; // unique

        $response = $this->put(route('users.update', $user->id), $request);

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }

    public function test_update_user_field_uuid_is_required(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $request = $this->prepareRequest();
        $request['uuid'] = null;

        $response = $this->put(route('users.update', $user->id), $request);

        $response->assertSessionHasErrors('uuid');
        $this->assertEquals('The uuid field is required.', session()->get('errors')->first('uuid'));
    }

    public function test_update_user_field_uuid_is_unique(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $user2 = User::factory()->create();
        $request = $this->prepareRequest();
        $request['uuid'] = $user2->uuid;

        $response = $this->put(route('users.update', $user->id), $request);

        $response->assertSessionHasErrors('uuid');
        $this->assertEquals('The uuid has already been taken.', session()->get('errors')->first('uuid'));
    }

    public function test_update_user_field_name_is_required(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $request = $this->prepareRequest();
        $request['name'] = null;

        $response = $this->put(route('users.update', $user->id), $request);

        $response->assertSessionHasErrors('name');
        $this->assertEquals('The name field is required.', session()->get('errors')->first('name'));
    }

    public function test_update_user_field_name_is_string(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $request = $this->prepareRequest();
        $request['name'] = 123123;

        $response = $this->put(route('users.update', $user->id), $request);

        $response->assertSessionHasErrors('name');
        $this->assertEquals('The name must be a string.', session()->get('errors')->first('name'));
    }

    public function test_update_user_field_email_is_required(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $request = $this->prepareRequest();
        $request['email'] = null;

        $response = $this->put(route('users.update', $user->id), $request);

        $response->assertSessionHasErrors('email');
        $this->assertEquals('The email field is required.', session()->get('errors')->first('email'));
    }

    public function test_update_user_field_email_is_unique(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $user2 = User::factory()->create();
        $request = $this->prepareRequest();
        $request['email'] = $user2->email;

        $response = $this->put(route('users.update', $user->id), $request);

        $response->assertSessionHasErrors('email');
        $this->assertEquals('The email has already been taken.', session()->get('errors')->first('email'));
    }

    public function test_update_user_field_email_is_email(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $request = $this->prepareRequest();
        $request['email'] = 'asdasdasd';

        $response = $this->put(route('users.update', $user->id), $request);

        $response->assertSessionHasErrors('email');
        $this->assertEquals('The email must be a valid email address.', session()->get('errors')->first('email'));
    }

    public function test_update_user_field_bank_is_required(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $request = $this->prepareRequest();
        $request['bank'] = null;

        $response = $this->put(route('users.update', $user->id), $request);

        $response->assertSessionHasErrors('bank');
        $this->assertEquals('The bank field is required.', session()->get('errors')->first('bank'));
    }

    public function test_update_user_field_bank_is_integer(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $request = $this->prepareRequest();
        $request['bank'] = 'asdfsdf';

        $response = $this->put(route('users.update', $user->id), $request);

        $response->assertSessionHasErrors('bank');
        $this->assertEquals('The bank must be an integer.', session()->get('errors')->first('bank'));
    }

    public function test_update_user_field_bank_must_exists(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $request = $this->prepareRequest();
        $request['bank'] = 999999999;

        $response = $this->put(route('users.update', $user->id), $request);

        $response->assertSessionHasErrors('bank');
        $this->assertEquals('The bank must be a valid bank.', session()->get('errors')->first('bank'));
    }

    public function test_update_user_field_bank_account_number_is_required(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $request = $this->prepareRequest();
        $request['bank_account_number'] = null;

        $response = $this->put(route('users.update', $user->id), $request);

        $response->assertSessionHasErrors('bank_account_number');
        $this->assertEquals('The bank account number field is required.', session()->get('errors')->first('bank_account_number'));
    }

    public function test_update_user_field_bank_account_number_is_numeric(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $request = $this->prepareRequest();
        $request['bank_account_number'] = 'asdasdasd';

        $response = $this->put(route('users.update', $user->id), $request);

        $response->assertSessionHasErrors('bank_account_number');
        $this->assertEquals('The bank account number must be a number.', session()->get('errors')->first('bank_account_number'));
    }

    public function test_update_user_field_divisi_id_is_required(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $request = $this->prepareRequest();
        $request['divisi_id'] = null;

        $response = $this->put(route('users.update', $user->id), $request);

        $response->assertSessionHasErrors('divisi_id');
        $this->assertEquals('The divisi id field is required.', session()->get('errors')->first('divisi_id'));
    }

    public function test_update_user_field_divisi_id_is_integer(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $request = $this->prepareRequest();
        $request['divisi_id'] = 'asdasdasd';

        $response = $this->put(route('users.update', $user->id), $request);

        $response->assertSessionHasErrors('divisi_id');
        $this->assertEquals('The divisi id must be an integer.', session()->get('errors')->first('divisi_id'));
    }

    public function test_update_user_field_divisi_id_is_exists(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $request = $this->prepareRequest();
        $request['divisi_id'] = 99999999;

        $response = $this->put(route('users.update', $user->id), $request);

        $response->assertSessionHasErrors('divisi_id');
        $this->assertEquals('The selected divisi id is invalid.', session()->get('errors')->first('divisi_id'));
    }

    public function test_update_user_field_posisi_id_is_required(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $request = $this->prepareRequest();
        $request['posisi_id'] = null;

        $response = $this->put(route('users.update', $user->id), $request);

        $response->assertSessionHasErrors('posisi_id');
        $this->assertEquals('The posisi id field is required.', session()->get('errors')->first('posisi_id'));
    }

    public function test_update_user_field_posisi_id_is_integer(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $request = $this->prepareRequest();
        $request['posisi_id'] = 'asdasdasd';

        $response = $this->put(route('users.update', $user->id), $request);

        $response->assertSessionHasErrors('posisi_id');
        $this->assertEquals('The posisi id must be an integer.', session()->get('errors')->first('posisi_id'));
    }

    public function test_update_user_field_posisi_id_is_exists(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $request = $this->prepareRequest();
        $request['posisi_id'] = 99999999;

        $response = $this->put(route('users.update', $user->id), $request);

        $response->assertSessionHasErrors('posisi_id');
        $this->assertEquals('The selected posisi id is invalid.', session()->get('errors')->first('posisi_id'));
    }

    public function test_update_user_field_join_date_is_required(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $request = $this->prepareRequest();
        $request['join_date'] = null;

        $response = $this->put(route('users.update', $user->id), $request);

        $response->assertSessionHasErrors('join_date');
        $this->assertEquals('The join date field is required.', session()->get('errors')->first('join_date'));
    }

    public function test_update_user_field_join_date_is_date(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $request = $this->prepareRequest();
        $request['join_date'] = 'asdasdasd';

        $response = $this->put(route('users.update', $user->id), $request);

        $response->assertSessionHasErrors('join_date');
        $this->assertEquals('The join date is not a valid date.', session()->get('errors')->first('join_date'));
    }

    public function test_update_user_field_cuti_is_required(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $request = $this->prepareRequest();
        $request['cuti'] = null;

        $response = $this->put(route('users.update', $user->id), $request);

        $response->assertSessionHasErrors('cuti');
        $this->assertEquals('The cuti field is required.', session()->get('errors')->first('cuti'));
    }

    public function test_update_user_field_cuti_is_integer(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $request = $this->prepareRequest();
        $request['cuti'] = 'asdasdasd';

        $response = $this->put(route('users.update', $user->id), $request);

        $response->assertSessionHasErrors('cuti');
        $this->assertEquals('The cuti must be an integer.', session()->get('errors')->first('cuti'));
    }

    public function test_update_user_field_salary_is_required(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $request = $this->prepareRequest();
        $request['salary'] = null;

        $response = $this->put(route('users.update', $user->id), $request);

        $response->assertSessionHasErrors('salary');
        $this->assertEquals('The salary field is required.', session()->get('errors')->first('salary'));
    }

    public function test_update_user_field_salary_is_numeric(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $request = $this->prepareRequest();
        $request['salary'] = 'asdasdasd';

        $response = $this->put(route('users.update', $user->id), $request);

        $response->assertSessionHasErrors('salary');
        $this->assertEquals('The salary must be a number.', session()->get('errors')->first('salary'));
    }

    public function test_can_update_user_if_authenticated(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        UserProfile::factory()->create([
            'user_id' => $user->id
        ]);

        $request = $this->prepareRequest();
        $request['name'] = 'test update';
        $request['join_date'] = '2021-01-01';

        $response = $this->put(route('users.update', $user->id), $request);

        $response->assertRedirect(route('users.index'));
        $response->assertSessionHas('toastr-success', 'User Successfully Updated');

        $this->assertDatabaseHas('users', [
            'uuid' => $request['uuid'],
            'name' => $request['name'],
            'email' => $request['email'],
        ]);

        $this->assertDatabaseHas('user_profile', [
            'user_id' => $user->id,
            'divisi_id' => $request['divisi_id'],
            'posisi_id' => $request['posisi_id'],
            'join_date' => $request['join_date'],
            'cuti' => $request['cuti'],
            'salary' => $request['salary'],
        ]);
    }

    public function test_cant_delete_user_if_not_authenticated(): void
    {
        $user = User::factory()->create();

        $response = $this->delete(route('users.destroy', $user->id));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }

    public function test_can_delete_user_if_authenticated(): void
    {
        $user = User::factory()->create();
        UserProfile::factory()->create([
            'user_id' => $user->id
        ]);

        $this->actingAs($user);

        $response = $this->delete(route('users.destroy', $user->id));

        $response->assertRedirect(route('users.index'));
        $response->assertSessionHas('toastr-success', 'User Successfully Deleted');

        $this->assertDatabaseMissing('users', [
            'id' => $user->id,
        ]);

        $this->assertDatabaseMissing('user_profile', [
            'user_id' => $user->id,
        ]);
    }
}
