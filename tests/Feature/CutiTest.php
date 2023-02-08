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
        $request['head_of_division'] = User::factory()->create()->id;
        $request['head_of_department'] = User::factory()->create()->id;

        unset($request['user_id']);
        return $request;
    }

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
        $user = $this->makeUserWithProfile();

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

    public function test_user_can_access_request_cuti_page_if_autheticated(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('cuti.request'));

        $response->assertStatus(200);
        $response->assertViewIs('cuti.request');
    }

    public function test_store_cuti_field_date_is_required(): void
    {
        $user = User::factory()->create();

        $request = $this->prepareRequest();
        $request['date'] = '';

        $response = $this->actingAs($user)->post(route('cuti.store'), $request);

        $response->assertSessionHasErrors('date');
        $this->assertEquals('The date field is required.', session()->get('errors')->first('date'));
    }

    public function test_store_cuti_field_from_must_be_date_range_format(): void
    {
        $user = User::factory()->create();

        $request = $this->prepareRequest();
        $request['date'] = 'asdasdasdsa';

        $response = $this->actingAs($user)->post(route('cuti.store'), $request);

        $response->assertSessionHasErrors('date');
        $this->assertEquals('The date format is invalid.', session()->get('errors')->first('date'));
    }

    public function test_store_cuti_field_reason_is_required(): void
    {
        $user = User::factory()->create();

        $request = $this->prepareRequest();
        $request['reason'] = '';

        $response = $this->actingAs($user)->post(route('cuti.store'), $request);

        $response->assertSessionHasErrors('reason');
        $this->assertEquals('The reason field is required.', session()->get('errors')->first('reason'));
    }

    public function test_store_cuti_field_reason_must_string(): void
    {
        $user = User::factory()->create();

        $request = $this->prepareRequest();
        $request['reason'] = 123;

        $response = $this->actingAs($user)->post(route('cuti.store'), $request);

        $response->assertSessionHasErrors('reason');
        $this->assertEquals('The reason must be a string.', session()->get('errors')->first('reason'));
    }

    public function test_store_cuti_success(): void
    {
        $user = User::factory()->create();

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
}
