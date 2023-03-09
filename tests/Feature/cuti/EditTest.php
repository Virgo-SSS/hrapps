<?php

namespace Tests\Feature\cuti;

use App\Models\Cuti;
use App\Models\CutiRequest;
use App\Models\User;
use Carbon\Carbon;

class EditTest extends baseCuti
{
    public function test_user_cant_redirect_to_edit_page_if_not_authenticated(): void
    {
        $cuti = Cuti::factory()->create();

        $response = $this->get(route('cuti.edit', $cuti->id));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }

    public function test_user_cant_redirect_to_edit_page_if_not_authorized(): void
    {
        $user = $this->createUserWithRoles('invalidRoles');
        $this->assignPermission('invalidPermission', $user);
        $cuti = Cuti::factory()->create();
        CutiRequest::factory()->create([
            'cuti_id' => $cuti->id,
            'status_hod' => config('cuti.status.pending'),
            'status_hodp' => config('cuti.status.pending'),
        ]);

        $response = $this->actingAs($user)->get(route('cuti.edit', $cuti->id));

        $response->assertStatus(403);
    }

    public function test_user_can_redirect_to_edit_page_if_authorized(): void
    {
        $user = $this->createUserWithRoles('employee');
        $this->assignPermission('edit cuti', $user);
        $hod = User::factory()->create();
        $hodp = User::factory()->create();

        $cuti = Cuti::factory()->create(['user_id' => $user->id]);
        CutiRequest::factory()->create([
            'cuti_id' => $cuti->id,
            'head_of_division' => $hod->id,
            'head_of_department' => $hodp->id,
            'status_hod' => config('cuti.status.pending'),
            'status_hodp' => config('cuti.status.pending'),
        ]);

        $response = $this->actingAs($user)->get(route('cuti.edit', $cuti->id));

        $response->assertStatus(200);
        $response->assertViewIs('cuti.edit');
        $response->assertViewHas('cuti');
    }

    public function test_super_admin_can_redirect_to_edit_page(): void
    {
        $user = $this->createUserWithRoles('super admin');
        $hod = User::factory()->create();
        $hodp = User::factory()->create();

        $cuti = Cuti::factory()->create(['user_id' => $user->id]);
        CutiRequest::factory()->create([
            'cuti_id' => $cuti->id,
            'head_of_division' => $hod->id,
            'head_of_department' => $hodp->id,
            'status_hod' => config('cuti.status.pending'),
            'status_hodp' => config('cuti.status.pending'),
        ]);

        $response = $this->actingAs($user)->get(route('cuti.edit', $cuti->id));

        $response->assertStatus(200);
        $response->assertViewIs('cuti.edit');
        $response->assertViewHas('cuti');
    }

    public function test_user_cant_update_cuti_if_not_authenticated(): void
    {
        $cuti = Cuti::factory()->create();

        $response = $this->put(route('cuti.update', $cuti->id));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }

    public function test_user_cant_update_cuti_if_not_authorized(): void
    {
        $user = $this->createUserWithRoles('invalidRoles');
        $this->assignPermission('invalidPermission', $user);
        $cuti = Cuti::factory()->create();
        CutiRequest::factory()->create([
            'cuti_id' => $cuti->id,
            'status_hod' => config('cuti.status.pending'),
            'status_hodp' => config('cuti.status.pending'),
        ]);

        $request = $this->prepareRequest();
        $response = $this->actingAs($user)->put(route('cuti.update', $cuti->id), $request);

        $response->assertStatus(403);
    }

    public function test_user_can_update_cuti_if_authorized(): void
    {
        $user = $this->createUserWithRoles('employee');
        $this->assignPermission('edit cuti', $user);
        $hod = User::factory()->create();
        $hodp = User::factory()->create();

        $cuti = Cuti::factory()->create(['user_id' => $user->id]);
        CutiRequest::factory()->create([
            'cuti_id' => $cuti->id,
            'head_of_division' => $hod->id,
            'head_of_department' => $hodp->id,
            'status_hod' => config('cuti.status.pending'),
            'status_hodp' => config('cuti.status.pending'),
        ]);

        $request = $this->prepareRequest();
        $request['date'] = Carbon::now()->addDays(1)->format('Y-m-d') . ' - ' . Carbon::now()->addDays(3)->format('Y-m-d');
        $response = $this->actingAs($user)->put(route('cuti.update', $cuti->id), $request);

        $response->assertStatus(302);
        $response->assertRedirect(route('cuti.edit', $cuti->id));
        $response->assertSessionHas('toastr-success', 'Cuti updated successfully.');

        $date = explode(' ', $request['date']);
        $this->assertDatabaseHas('cuti', [
            'id' => $cuti->id,
            'user_id' => $user->id,
            'from' => $date[0],
            'to' => $date[2],
            'reason' => $request['reason'],
            'status' => config('cuti.status.pending'),
        ]);

        $this->assertDatabaseHas('cuti_request', [
            'cuti_id' => $cuti->id,
            'head_of_division' => $request['head_of_division'],
            'status_hod' => config('cuti.status.pending'),
            'note_hod' => $cuti->cutiRequest->note_hod,
            'head_of_department' => $request['head_of_department'],
            'status_hodp' => config('cuti.status.pending'),
            'note_hodp' => $cuti->cutiRequest->note_hodp,
        ]);
    }

    public function test_super_admin_can_update_cuti(): void
    {
        $user = $this->createUserWithRoles('super admin');
        $hod = User::factory()->create();
        $hodp = User::factory()->create();

        $cuti = Cuti::factory()->create(['user_id' => $user->id]);
        CutiRequest::factory()->create([
            'cuti_id' => $cuti->id,
            'head_of_division' => $hod->id,
            'head_of_department' => $hodp->id,
            'status_hod' => config('cuti.status.pending'),
            'status_hodp' => config('cuti.status.pending'),
        ]);

        $request = $this->prepareRequest();
        $request['date'] = Carbon::now()->addDays(1)->format('Y-m-d') . ' - ' . Carbon::now()->addDays(3)->format('Y-m-d');
        $response = $this->actingAs($user)->put(route('cuti.update', $cuti->id), $request);

        $response->assertStatus(302);
        $response->assertRedirect(route('cuti.index'));
        $response->assertSessionHas('toastr-success', 'Cuti updated successfully.');

        $date = explode(' ', $request['date']);
        $this->assertDatabaseHas('cuti', [
            'id' => $cuti->id,
            'user_id' => $user->id,
            'from' => $date[0],
            'to' => $date[2],
            'reason' => $request['reason'],
            'status' => config('cuti.status.pending'),
        ]);

        $this->assertDatabaseHas('cuti_request', [
            'cuti_id' => $cuti->id,
            'head_of_division' => $request['head_of_division'],
            'status_hod' => config('cuti.status.pending'),
            'note_hod' => $cuti->cutiRequest->note_hod,
            'head_of_department' => $request['head_of_department'],
            'status_hodp' => config('cuti.status.pending'),
            'note_hodp' => $cuti->cutiRequest->note_hodp,
        ]);
    }
}
