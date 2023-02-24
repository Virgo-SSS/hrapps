<?php

namespace Tests\Feature;

use App\Models\Cuti;
use App\Models\CutiRequest;
use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CutiTest extends TestCase
{
    private function prepareRequest(): array
    {
        $request = Cuti::factory()->make()->toArray();
        $request['date'] = $request['from'] . ' - ' . $request['to'];
        $request['head_of_division'] = User::factory()->create()->id;
        $request['head_of_department'] = User::factory()->create()->id;

        unset($request['user_id']);
        unset($request['from']);
        unset($request['to']);
        return $request;
    }

    public function test_user_cant_access_cuti_page_if_not_authenticated(): void
    {
        $response = $this->get(route('cuti.index'));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }

    public function test_user_cant_access_cuti_page_if_unauthorized(): void
    {
        $user = $this->createUserWithRoles('invalidRoles');
        $this->assignPermission('invalidpermission', $user);

        $response = $this->actingAs($user)->get(route('cuti.index'));

        $response->assertStatus(403);
    }

    public function test_super_admin_can_access_cuti_page_if_authenticated(): void
    {
        $user = $this->createUserWithRoles('super admin');

        $response = $this->actingAs($user)->get(route('cuti.index'));

        $response->assertStatus(200);
        $response->assertViewIs('cuti.index');
        $response->assertViewHas('cutis');
    }

    public function test_user_can_access_cuti_page_if_authorized(): void
    {
        $user = $this->createUserWithRoles('employee');
        $this->assignPermission('view cuti', $user);

        $response = $this->actingAs($user)->get(route('cuti.index'));

        $response->assertStatus(200);
        $response->assertViewIs('cuti.index');
        $response->assertViewHas('cutis');
    }

    public function test_user_cant_access_create_cuti_page_if_not_authenticated(): void
    {
        $response = $this->get(route('cuti.create'));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }

    public function test_user_can_access_create_cuti_page_if_authenticated(): void
    {
        $user = $this->createUserWithRoles('super admin');
        UserProfile::factory()->create([
            'user_id' => $user->id,
        ]);

        $response = $this->actingAs($user)->get(route('cuti.create'));

        $response->assertStatus(200);
        $response->assertViewIs('cuti.create');
    }

    public function test_user_cant_access_request_cuti_page_if_not_authenticated(): void
    {
        $response = $this->get(route('cuti.request'));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }

    public function test_user_cant_access_request_cuti_page_if_unauthorized(): void
    {
        $user = $this->createUserWithRoles('invalidRoles');
        $this->assignPermission('invalidpermission', $user);

        $response = $this->actingAs($user)->get(route('cuti.request'));

        $response->assertStatus(403);
    }

    public function test_super_admin_can_access_request_cuti_page_if_autheticated(): void
    {
        $user = $this->createUserWithRoles('super admin');

        $response = $this->actingAs($user)->get(route('cuti.request'));

        $response->assertStatus(200);
        $response->assertViewIs('cuti.request');
    }

    public function test_user_can_access_request_cuti_page_if_authorized(): void
    {
        $user = $this->createUserWithRoles('employee');
        $this->assignPermission('view cuti', $user);

        $response = $this->actingAs($user)->get(route('cuti.request'));

        $response->assertStatus(200);
        $response->assertViewIs('cuti.request');
    }

    public function test_store_cuti_field_date_is_required(): void
    {
        $user = $this->createUserWithRoles('super admin');

        $request = $this->prepareRequest();
        $request['date'] = '';

        $response = $this->actingAs($user)->post(route('cuti.store'), $request);

        $response->assertSessionHasErrors('date');
        $this->assertEquals('The date field is required.', session()->get('errors')->first('date'));
    }

    public function test_store_cuti_field_from_must_be_date_range_format(): void
    {
        $user = $this->createUserWithRoles('super admin');

        $request = $this->prepareRequest();
        $request['date'] = 'asdasdasdsa';

        $response = $this->actingAs($user)->post(route('cuti.store'), $request);

        $response->assertSessionHasErrors('date');
        $this->assertEquals('The date format is invalid.', session()->get('errors')->first('date'));
    }

    public function test_store_cuti_field_reason_is_required(): void
    {
        $user = $this->createUserWithRoles('super admin');

        $request = $this->prepareRequest();
        $request['reason'] = '';

        $response = $this->actingAs($user)->post(route('cuti.store'), $request);

        $response->assertSessionHasErrors('reason');
        $this->assertEquals('The reason field is required.', session()->get('errors')->first('reason'));
    }

    public function test_store_cuti_field_reason_must_string(): void
    {
        $user = $this->createUserWithRoles('super admin');

        $request = $this->prepareRequest();
        $request['reason'] = 123;

        $response = $this->actingAs($user)->post(route('cuti.store'), $request);

        $response->assertSessionHasErrors('reason');
        $this->assertEquals('The reason must be a string.', session()->get('errors')->first('reason'));
    }

    public function test_super_admin_store_cuti_success(): void
    {
        $user = $this->createUserWithRoles('super admin');

        $request = $this->prepareRequest();

        $response = $this->actingAs($user)->post(route('cuti.store'), $request);

        $response->assertStatus(302);
        $response->assertRedirect(route('cuti.index'));
        $response->assertSessionHas('toastr-success', 'Cuti created successfully.');

        $date = explode(' ', $request['date']);
        $this->assertDatabaseHas('cuti', [
            'user_id' => $user->id,
            'from' => $date[0],
            'to' => $date[2],
            'reason' => $request['reason'],
            'status' => config('cuti.status.pending'),
        ]);

        $this->assertDatabaseHas('cuti_request', [
            'cuti_id' => Cuti::where('user_id', $user->id)->first()->id,
            'head_of_division' => $request['head_of_division'],
            'status_hod' => config('cuti.status.pending'),
            'note_hod' => null,
            'head_of_department' => $request['head_of_department'],
            'status_hodp' => config('cuti.status.pending'),
            'note_hodp' => null,
        ]);
    }

    public function test_user_store_cuti_success_if_authorized(): void
    {
        $user = $this->createUserWithRoles('employee');
        $this->assignPermission('create cuti', $user);

        $request = $this->prepareRequest();

        $response = $this->actingAs($user)->post(route('cuti.store'), $request);

        $response->assertStatus(302);
        $response->assertRedirect(route('cuti.index'));
        $response->assertSessionHas('toastr-success', 'Cuti created successfully.');

        $date = explode(' ', $request['date']);
        $this->assertDatabaseHas('cuti', [
            'user_id' => $user->id,
            'from' => $date[0],
            'to' => $date[2],
            'reason' => $request['reason'],
            'status' => config('cuti.status.pending'),
        ]);

        $this->assertDatabaseHas('cuti_request', [
            'cuti_id' => Cuti::where('user_id', $user->id)->first()->id,
            'head_of_division' => $request['head_of_division'],
            'status_hod' => config('cuti.status.pending'),
            'note_hod' => null,
            'head_of_department' => $request['head_of_department'],
            'status_hodp' => config('cuti.status.pending'),
            'note_hodp' => null,
        ]);
    }

    public function test_user_cant_go_to_cuti_detaill_if_not_authenticated(): void
    {
        $cuti = Cuti::factory()->create();

        $response = $this->get(route('cuti.show', $cuti->id));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }

    public function test_user_cant_redirect_to_cuti_detail_if_not_authorized(): void
    {
        $user = $this->createUserWithRoles('invalidRoles');
        $this->assignPermission('invalidPermission', $user);
        $cuti = Cuti::factory()->create();

        $response = $this->actingAs($user)->get(route('cuti.show', $cuti->id));

        $response->assertStatus(403);
    }

    public function test_super_admin_can_go_to_cuti_detail_if_authenticated(): void
    {
        $user = $this->createUserWithRoles('super admin');
        $hod = User::factory()->create();
        $hodp = User::factory()->create();

        $cuti = Cuti::factory()->create(['user_id' => $user->id]);
        CutiRequest::factory()->create([
            'cuti_id' => $cuti->id,
            'head_of_division' => $hod->id,
            'head_of_department' => $hodp->id,
        ]);

        $response = $this->actingAs($user)->get(route('cuti.show', $cuti->id));

        $response->assertStatus(200);
        $response->assertViewIs('cuti.detail');
        $response->assertViewHas('cuti');
    }

    public function test_user_can_go_to_cuti_detail_if_authorized(): void
    {
        $user = $this->createUserWithRoles('employee');
        $this->assignPermission('view cuti', $user);
        $hod = User::factory()->create();
        $hodp = User::factory()->create();

        $cuti = Cuti::factory()->create(['user_id' => $user->id]);
        CutiRequest::factory()->create([
            'cuti_id' => $cuti->id,
            'head_of_division' => $hod->id,
            'head_of_department' => $hodp->id,
        ]);

        $response = $this->actingAs($user)->get(route('cuti.show', $cuti->id));

        $response->assertStatus(200);
        $response->assertViewIs('cuti.detail');
        $response->assertViewHas('cuti');
    }

    public function test_user_cant_delete_cuti_if_unautheticated(): void
    {
        $cuti = Cuti::factory()->create();

        $response = $this->delete(route('cuti.destroy', $cuti->id));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }

    public function test_user_cant_delete_cuti_if_unauthorized(): void
    {
        $user = $this->createUserWithRoles('invalidRoles');
        $this->assignPermission('invalidPermission', $user);
        $cuti = Cuti::factory()->create();

        $response = $this->actingAs($user)->delete(route('cuti.destroy', $cuti->id));

        $response->assertStatus(403);
    }

    public function test_super_admin_can_delete_cuti_if_authenticated(): void
    {
        $user = $this->createUserWithRoles('super admin');

        $cuti = Cuti::factory()->create(['user_id' => $user->id]);
        CutiRequest::factory()->create(['cuti_id' => $cuti->id]);

        $response = $this->actingAs($user)->delete(route('cuti.destroy', $cuti->id));

        $response->assertStatus(302);
        $response->assertRedirect(route('cuti.index'));
        $response->assertSessionHas('toastr-success', 'Cuti deleted successfully.');

        $this->assertDatabaseMissing('cuti', [
            'id' => $cuti->id,
        ]);

        $this->assertDatabaseMissing('cuti_request', [
            'cuti_id' => $cuti->id,
        ]);
    }

    public function test_user_can_delete_cuti_if_authorized(): void
    {
        $user = $this->createUserWithRoles('employee');
        $this->assignPermission('delete cuti', $user);

        $cuti = Cuti::factory()->create(['user_id' => $user->id]);
        CutiRequest::factory()->create(['cuti_id' => $cuti->id]);

        $response = $this->actingAs($user)->delete(route('cuti.destroy', $cuti->id));

        $response->assertStatus(302);
        $response->assertRedirect(route('cuti.index'));
        $response->assertSessionHas('toastr-success', 'Cuti deleted successfully.');

        $this->assertDatabaseMissing('cuti', [
            'id' => $cuti->id,
        ]);

        $this->assertDatabaseMissing('cuti_request', [
            'cuti_id' => $cuti->id,
        ]);
    }
}
