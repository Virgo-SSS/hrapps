<?php

namespace Tests\Feature\cuti;

use App\Models\Cuti;
use App\Models\CutiRequest;

class DeleteTest extends baseCuti
{
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
