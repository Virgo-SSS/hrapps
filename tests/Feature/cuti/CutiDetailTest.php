<?php

namespace Tests\Feature\cuti;

use App\Models\Cuti;
use App\Models\CutiRequest;
use App\Models\User;

class CutiDetailTest extends baseCuti
{
    /**
    * @test
    */
    public function user_cant_go_to_cuti_detail_if_not_authenticated(): void
    {
        $cuti = Cuti::factory()->create();

        $response = $this->get(route('cuti.show', $cuti->id));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }

    /**
     * @test
     */
    public function user_cant_redirect_to_cuti_detail_if_not_authorized(): void
    {
        $user = $this->createUserWithRoles('invalidRoles');
        $this->assignPermission('invalidPermission', $user);
        $cuti = Cuti::factory()->create();

        $response = $this->actingAs($user)->get(route('cuti.show', $cuti->id));

        $response->assertStatus(403);
    }

    /**
     * @test
     * @dataProvider userIndex
     */
    public function users_can_go_to_cuti_detail_if_authenticated(string $role, ?string $permission = null): void
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
        ]);

        $response = $this->actingAs($user)->get(route('cuti.show', $cuti->id));

        $response->assertStatus(200);
        $response->assertViewIs('cuti.detail');
        $response->assertViewHas('cuti');
    }
}
