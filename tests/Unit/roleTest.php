<?php

namespace Tests\Unit;

use App\Http\Middleware\roleEdit;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class roleTest extends TestCase
{
    public function test_middleware_edit_role_that_user_not_super_admin_trying_to_edit_super_admin(): void
    {
        $user = User::factory()->create(['name' => 'not super admin']);
        $role = Role::create(['name' => 'super admin']);

        $request = \Mockery::mock(Request::class);
        $request->shouldReceive('route')->with('role')->andReturn($role);

        $this->actingAs($user);
        $closure = function ($request) {
            return response('OK');
        };

        $middleware = new roleEdit();

        $this->expectException(\Symfony\Component\HttpKernel\Exception\HttpException::class);
        $this->expectExceptionMessage('You are not allowed to edit this role.');

        $middleware->handle($request, $closure);

    }

    public function test_middleware_super_admin_can_edit_super_admin_role(): void
    {
        $user = User::factory()->create(['name' => 'super admin']);
        $role = Role::create(['name' => 'super admin']);

        $request = \Mockery::mock(Request::class);
        $request->shouldReceive('route')->with('role')->andReturn($role);

        $this->actingAs($user);
        $closure = function ($request) {
            return response('OK');
        };

        $middleware = new roleEdit();
        $response =  $middleware->handle($request, $closure);

        $this->assertEquals('OK', $response->getContent());
        $this->assertEquals(200, $response->status());
    }
}
