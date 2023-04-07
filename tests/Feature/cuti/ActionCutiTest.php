<?php

namespace Tests\Feature\cuti;

use App\Models\Cuti;
use App\Models\CutiRequest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ActionCutiTest extends baseCuti
{
    public function test_user_cant_access_approve_or_reject_route_if_not_authenticated(): void
    {
        $cuti = Cuti::factory()->create();

        $this->put(route('cuti.approve', $cuti->id))
            ->assertRedirect(route('login'));

        $this->put(route('cuti.reject', $cuti->id))
            ->assertRedirect(route('login'));
    }

    public function test_user_cant_access_approve_or_reject_route_if_not_authorized(): void
    {
        $user = $this->createUserWithRoles('invalid roles');
        $this->assignPermission('invalid permission', $user);

        $this->actingAs($user);

        $cuti = Cuti::factory()->create();
        $request = ['status' => 'approve'];

        $this->put(route('cuti.approve', $cuti->id), $request)
            ->assertStatus(403);

        $this->put(route('cuti.reject', $cuti->id), $request)
            ->assertStatus(403);
    }

    /**
     * @dataProvider actionLeaveProvider
     */
    public function test_validation_action_leave(string $field, string|int $value, string $expected): void
    {
        $user = $this->createUserWithRoles('employee');
        $this->assignPermission('view cuti request', $user);

        $this->actingAs($user);

        $cuti = Cuti::factory()->create();

        $this->put(route('cuti.approve', $cuti->id), [
            $field => $value
        ])->assertSessionHasErrors($field, $expected);
    }

    private function actionLeaveProvider(): array
    {
        return [
            'note is string' => [
                'note', 123, 'The note must be a string.'
            ],
            'status is required' => [
                'status', '', 'The staus field is required.'
            ],

            'status must be approve or reject' => [
                'status', 'invalid', 'The selected staus is invalid.'
            ],
        ];
    }

    public function test_head_of_division_can_approve_leave_request(): void
    {
        $user = $this->createUserWithRoles('employee');
        $this->assignPermission('view cuti request', $user);

        $this->actingAs($user);

        $cuti = Cuti::factory()->create();
        $cutiRequest = CutiRequest::factory()->create([
            'cuti_id' => $cuti->id,
            'head_of_division' => $user->id,
            'status_hod' => config('cuti.status.pending'),
        ]);

        $this->put(route('cuti.approve', $cuti->id), [
            'note' => 'test approve head of division',
            'status' => 'approve'
        ])->assertRedirect(route('cuti.pending'));

        $this->assertDatabaseHas('cuti_request', [
            'id' => $cutiRequest->id,
            'status_hod' => config('cuti.status.approved'),
            'note_hod' => 'test approve head of division',
        ]);
    }

    public function test_head_of_department_can_approve_leave_request(): void
    {
        $user = $this->createUserWithRoles('employee');
        $this->assignPermission('view cuti request', $user);

        $this->actingAs($user);

        $cuti = Cuti::factory()->create();
        $cutiRequest = CutiRequest::factory()->create([
            'cuti_id' => $cuti->id,
            'status_hod' => 1,
            'head_of_department' => $user->id,
            'status_hodp' => config('cuti.status.pending'),
        ]);

        $this->put(route('cuti.approve', $cuti->id), [
            'note' => 'test approve head of department',
            'status' => 'approve'
        ])->assertRedirect(route('cuti.pending'));

        $this->assertDatabaseHas('cuti_request', [
            'id' => $cutiRequest->id,
            'status_hod' => config('cuti.status.approved'),
            'status_hodp' => config('cuti.status.approved'),
            'note_hodp' => 'test approve head of department',
        ]);

        $this->assertDatabaseHas('cuti', [
            'id' => $cuti->id,
            'status' => config('cuti.status.approved'),
        ]);
    }

    public function test_head_of_division_can_reject_leave_request(): void
    {
        $user = $this->createUserWithRoles('employee');
        $this->assignPermission('view cuti request', $user);

        $this->actingAs($user);

        $cuti = Cuti::factory()->create();
        $cutiRequest = CutiRequest::factory()->create([
            'cuti_id' => $cuti->id,
            'head_of_division' => $user->id,
            'status_hod' => config('cuti.status.pending'),
        ]);

        $this->put(route('cuti.reject', $cuti->id), [
            'note' => 'test reject head of division',
            'status' => 'reject'
        ])->assertRedirect(route('cuti.pending'));

        $this->assertDatabaseHas('cuti_request', [
            'id' => $cutiRequest->id,
            'status_hod' => config('cuti.status.rejected'),
            'note_hod' => 'test reject head of division',
        ]);
    }

    public function test_head_of_department_can_reject_leave_request(): void
    {
        $user = $this->createUserWithRoles('employee');
        $this->assignPermission('view cuti request', $user);

        $this->actingAs($user);

        $cuti = Cuti::factory()->create();
        $cutiRequest = CutiRequest::factory()->create([
            'cuti_id' => $cuti->id,
            'status_hod' => 1,
            'head_of_department' => $user->id,
            'status_hodp' => config('cuti.status.pending'),
        ]);

        $this->put(route('cuti.approve', $cuti->id), [
            'note' => 'test reject head of department',
            'status' => 'reject'
        ])->assertRedirect(route('cuti.pending'));

        $this->assertDatabaseHas('cuti_request', [
            'id' => $cutiRequest->id,
            'status_hod' => config('cuti.status.approved'),
            'status_hodp' => config('cuti.status.rejected'),
            'note_hodp' => 'test reject head of department',
        ]);

        $this->assertDatabaseHas('cuti', [
            'id' => $cuti->id,
            'status' => config('cuti.status.rejected'),
        ]);
    }
}
