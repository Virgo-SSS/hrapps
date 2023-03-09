<?php

namespace Tests\Feature\divisi;

use App\Models\Divisi;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class deleteTest extends TestCase
{
    public function test_user_cant_delete_divisi_if_user_unauthenticated(): void
    {
        $divisi = Divisi::factory()->create();

        $response = $this->delete(route('divisi.destroy', $divisi));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertDatabaseHas('divisi', [
            'id' => $divisi->id,
        ]);
    }

    public function test_user_cant_delete_divisi_if_user_unauthorized(): void
    {
        $user  = $this->createUserWithRoles('invalidRoles');
        $this->assignPermission('invalidPermission', $user);
        $this->actingAs($user);

        $divisi = Divisi::factory()->create();

        $response = $this->delete(route('divisi.destroy', $divisi));

        $response->assertStatus(403);
        $this->assertDatabaseHas('divisi', [
            'id' => $divisi->id,
        ]); }

    public function test_super_admin_can_delete_divisi_if_user_authenticated(): void
    {
        $user = $this->createUserWithRoles('super admin');
        $this->actingAs($user);

        $divisi = Divisi::factory()->create();

        $response = $this->delete(route('divisi.destroy', $divisi));

        $response->assertStatus(302);
        $response->assertRedirect(route('divisi.index'));
        $response->assertSessionHas('toastr-success', 'Divisi Successfully Deleted');
        $this->assertDatabaseMissing('divisi', [
            'id' => $divisi->id,
        ]);
    }

    public function test_user_can_delete_divisi_if_user_authorized(): void
    {
        $user = $this->createUserWithRoles('employee');
        $this->assignPermission('delete division', $user);
        $this->actingAs($user);

        $divisi = Divisi::factory()->create();

        $response = $this->delete(route('divisi.destroy', $divisi));

        $response->assertStatus(302);
        $response->assertRedirect(route('divisi.index'));
        $response->assertSessionHas('toastr-success', 'Divisi Successfully Deleted');
        $this->assertDatabaseMissing('divisi', [
            'id' => $divisi->id,
        ]);
    }
}
