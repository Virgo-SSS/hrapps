<?php

namespace Tests\Unit;

use App\Http\Middleware\cutiEdit;
use App\Http\Middleware\roleEdit;
use App\Models\Cuti;
use App\Models\CutiRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class CutiTest extends TestCase
{
    public function test_duration_accessor_attribute(): void
    {
        $cuti = new Cuti([
            'from' => '2021-01-01',
            'to' => '2021-01-03',
        ]);

        $this->assertEquals(3, $cuti->duration);
    }

    public function test_get_status_in_human_returns_correct_string(): void
    {
        $cuti = new Cuti();

        $cuti->status = 0;
        $this->assertEquals('Pending', $cuti->status_in_human);

        $cuti->status = 1;
        $this->assertEquals('Approved', $cuti->status_in_human);

        $cuti->status = 2;
        $this->assertEquals('Rejected', $cuti->status_in_human);
    }

    public function test_get_status_hod_in_human_returns_correct_string_for_cutiRequest(): void
    {
        $cuti = new CutiRequest();

        $cuti->status_hod = 0;
        $this->assertEquals('Pending', $cuti->status_hod_in_human);

        $cuti->status_hod = 1;
        $this->assertEquals('Approved', $cuti->status_hod_in_human);

        $cuti->status_hod = 2;
        $this->assertEquals('Rejected', $cuti->status_hod_in_human);
    }

    public function test_get_status_hodp_in_human_returns_correct_string_for_cutiRequest(): void
    {
        $cuti = new CutiRequest();

        $cuti->status_hodp = 0;
        $this->assertEquals('Pending', $cuti->status_hodp_in_human);

        $cuti->status_hodp = 1;
        $this->assertEquals('Approved', $cuti->status_hodp_in_human);

        $cuti->status_hodp = 2;
        $this->assertEquals('Rejected', $cuti->status_hodp_in_human);
    }

    public function test_color_status_cuti_return_correct_color(): void
    {
        $cuti = new Cuti();

        $cuti->status = 0;
        $this->assertEquals('warning', $cuti->color_status);

        $cuti->status = 1;
        $this->assertEquals('success', $cuti->color_status);

        $cuti->status = 2;
        $this->assertEquals('danger', $cuti->color_status);
    }

    public function test_color_status_hod_cuti_request_return_correct_color(): void
    {
        $cuti = new CutiRequest();

        $cuti->status_hod = 0;
        $this->assertEquals('warning', $cuti->color_status_hod);

        $cuti->status_hod = 1;
        $this->assertEquals('success', $cuti->color_status_hod);

        $cuti->status_hod = 2;
        $this->assertEquals('danger', $cuti->color_status_hod);
    }

    public function test_color_status_hodp_cuti_request_return_correct_color(): void
    {
        $cuti = new CutiRequest();

        $cuti->status_hodp = 0;
        $this->assertEquals('warning', $cuti->color_status_hodp);

        $cuti->status_hodp = 1;
        $this->assertEquals('success', $cuti->color_status_hodp);

        $cuti->status_hodp = 2;
        $this->assertEquals('danger', $cuti->color_status_hodp);
    }

    public function test_Pending_Scope(): void
    {
        $pendingCuti = Cuti::factory()->create([
            'status' => 0,
        ]);
        $notPendingCuti = Cuti::factory()->create([
            'status' => 1,
        ]);

        $cuti = Cuti::Pending()->get();

        $this->assertCount(1,  $cuti);
        $this->assertTrue($cuti->contains($pendingCuti));
        $this->assertFalse($cuti->contains($notPendingCuti));
    }

    public function test_cuti_date_return_correct_date(): void
    {
        $cuti = new Cuti([
            'from' => '2021-01-01',
            'to' => '2021-01-03',
        ]);

        $this->assertEquals('2021-01-01 - 2021-01-03', $cuti->date_cuti);
        $this->assertTrue(is_string($cuti->date_cuti));
    }

    public function test_total_leave_days_return_correct_date(): void
    {
        $cuti = new Cuti([
            'from' => '2021-01-01',
            'to' => '2021-01-03',
        ]);

        $this->assertEquals(3, $cuti->total_leave_days);
        $this->assertTrue(is_int($cuti->total_leave_days));
    }

    public function test_middleware_cutiEdit_if_one_of_status_hod_or_hodp_already_accepted_user_cant_edit_cuti(): void
    {
        $user = User::factory()->create(['name' => 'not super admin']);

        $cuti = Cuti::factory()->create(['user_id' => $user->id]);
        CutiRequest::factory()->create([
            'cuti_id' => $cuti->id,
            'status_hod' => 1,
        ]);

        $request = \Mockery::mock(Request::class);
        $request->shouldReceive('route')->with('cuti')->andReturn($cuti);

        $closure = function ($request) {
            return response('OK');
        };

        $middleware = new cutiEdit();
        $response = $middleware->handle($request, $closure);

        $this->assertEquals(302, $response->status());
        $this->assertEquals('Cuti cannot be edited It has been approved or rejected. Please contact the administrator.', session('swal-error'));
    }

    public function test_middleware_cutiEdit_user_can_edit_cuti_if_status_hod_and_hodp_still_pending(): void
    {
        $user = User::factory()->create(['name' => 'not super admin']);

        $cuti = Cuti::factory()->create(['user_id' => $user->id]);
        CutiRequest::factory()->create([
            'cuti_id' => $cuti->id,
            'status_hod' => 0,
            'status_hodp' => 0,
        ]);

        $request = \Mockery::mock(Request::class);
        $request->shouldReceive('route')->with('cuti')->andReturn($cuti);

        $closure = function ($request) {
            return response('OK');
        };

        $middleware = new cutiEdit();


        $response = $middleware->handle($request, $closure);

        $this->assertEquals(200, $response->status());
        $this->assertEquals('OK', $response->getContent());
    }
}
