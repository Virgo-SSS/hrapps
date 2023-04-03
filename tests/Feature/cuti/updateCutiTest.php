<?php

namespace Tests\Feature\cuti;

use App\Models\Cuti;
use App\Models\CutiRequest;
use App\Models\User;
use Tests\Feature\cuti\baseCuti;

class updateCutiTest extends baseCuti
{
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

    /**
     * @test
     * @dataProvider DataFormUpdateCuti
     */
    public function validation_form_request(string $field, string|int $value, string $errorMessage): void
    {
        $user = $this->createUserWithRoles('super admin');

        $cuti = Cuti::factory()->create();
        CutiRequest::factory()->create([
            'cuti_id' => $cuti->id,
            'status_hod' => config('cuti.status.pending'),
            'status_hodp' => config('cuti.status.pending'),
        ]);

        $request = $this->prepareRequest();
        $request[$field] = $value;

        $response = $this->actingAs($user)->put(route('cuti.update', $cuti->id), $request);

        $response->assertSessionHasErrors($field);
        $this->assertEquals($errorMessage, session()->get('errors')->first($field));
    }

    private function DataFormUpdateCuti(): array
    {
        return [
            'reason is required' => [
                'reason',
                '',
                'The reason field is required.',
            ],
            'reason is string' => [
                'reason',
                123,
                'The reason must be a string.',
            ],
            'head_of_division is required' => [
                'head_of_division',
                '',
                'The head of division field is required.',
            ],
            'head_of_division is not valid' => [
                'head_of_division',
                9999999999,
                'The selected head of division is invalid.',
            ],
            'head_of_department is required' => [
                'head_of_department',
                '',
                'The head of department field is required.',
            ],
            'head_of_department is not valid' => [
                'head_of_department',
                9999999999,
                'The selected head of department is invalid.',
            ],
        ];
    }

    /**
     * @test
     * @dataProvider userEdit
     */
    public function test_user_can_update_cuti_if_authorized(string $role, ?string $permission = null): void
    {
        $user = $this->createUserWithRoles($role);
        if($permission) {
            $this->assignPermission($permission, $user);
        }

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
        $response = $this->actingAs($user)->put(route('cuti.update', $cuti->id), $request);

        $response->assertStatus(302);
        $response->assertRedirect(route('cuti.edit', $cuti->id));
        $response->assertSessionHas('toastr-success', 'Cuti updated successfully.');

        $this->assertDatabaseHas('cuti', [
            'id' => $cuti->id,
            'user_id' => $user->id,
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
