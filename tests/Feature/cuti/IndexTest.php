<?php

namespace Tests\Feature\cuti;

class IndexTest extends baseCuti
{
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

    /**
     * @dataProvider userIndex
     */
    public function test_user_can_access_cuti_page_if_authenticated(string $role, ?string $permission = null): void
    {
        $user = $this->createUserWithRoles($role);
        if($permission) {
            $this->assignPermission($permission, $user);
        }

        $response = $this->actingAs($user)->get(route('cuti.index'));

        $response->assertStatus(200);
        $response->assertViewIs('cuti.index');
        $response->assertViewHas('cutis');
    }
}
