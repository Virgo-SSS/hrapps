<?php

namespace Tests\Feature\cuti;

use App\Interfaces\CutiRepositoryInterface;
use App\Models\Cuti;
use App\Models\CutiRequest;
use App\Models\User;
use App\Repository\CutiRepository;
use Tests\Feature\cuti\baseCuti;

class CutiRequestPageTest extends baseCuti
{
    public function test_user_cant_access_request_cuti_page_if_not_authenticated(): void
    {
        $response = $this->get(route('cuti.pending'));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }

    public function test_user_cant_access_request_cuti_page_if_unauthorized(): void
    {
        $user = $this->createUserWithRoles('invalidRoles');
        $this->assignPermission('invalidpermission', $user);

        $response = $this->actingAs($user)->get(route('cuti.pending'));

        $response->assertStatus(403);
    }

    /**
     * @dataProvider userViewCutiRequest
     */
    public function test_super_admin_can_access_request_cuti_page_if_autheticated(string $role, ?string $permission = null): void
    {
        $user = $this->createUserWithRoles($role);
        if($permission) {
            $this->assignPermission($permission, $user);
        }

        $response = $this->actingAs($user)->get(route('cuti.pending'));

        $response->assertStatus(200);
        $response->assertViewIs('cuti.pending');
    }

    /**
     * @dataProvider headLeave
     */
    public function test_head_get_the_right_leave_pending_data(string $loginAs, int $expected1, int $expected2): void
    {
        $hod = $this->createUserWithRoles('head of division');
        $hodp = $this->createUserWithRoles('head of apartement');
        $this->actingAs(${$loginAs});
        $cuti =  Cuti::factory()->create();
        $cuti->cutiRequest()->create([
            'head_of_division' => $hod->id,
            'head_of_department' => $hodp->id,
            'status_hod' => config('cuti.status.pending'),
            'status_hodp' => config('cuti.status.pending'),
        ]);
        $repository = $this->app->make(CutiRepositoryInterface::class);
        $pending = $repository->getPendingCuti();

        $this->assertEquals($expected1, $pending->count());

        CutiRequest::where('cuti_id', $cuti->id)->update([
            'status_hod' => config('cuti.status.approved'),
        ]);

        $pending = $repository->getPendingCuti();
        $this->assertEquals($expected2, $pending->count());
    }

    protected function headLeave(): array
    {
        return [
            'head of division' => ['hod', 1,0],
            'head of department' => ['hodp',0,1],
        ];
    }
}
