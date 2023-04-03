<?php

namespace Tests\Feature\cuti;

use App\Models\Cuti;
use App\Models\CutiRequest;
use App\Models\User;

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


    /**
     * @dataProvider userEdit
     */
    public function test_user_can_redirect_to_edit_page(string $role, ?string $permission = null): void
    {
        $user = $this->createUserWithRoles($role);
        if($permission){
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

        $response = $this->actingAs($user)->get(route('cuti.edit', $cuti->id));

        $response->assertStatus(200);
        $response->assertViewIs('cuti.edit');
        $response->assertViewHas('cuti');
    }
}
