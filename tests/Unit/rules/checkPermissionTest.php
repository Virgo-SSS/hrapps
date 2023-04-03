<?php

namespace Tests\Unit\rules;

use App\Rules\checkPermission;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;

class checkPermissionTest extends TestCase
{
    public function test_check_permission(): void
    {
        $permission = Permission::create(['name' => 'test']);
        $rule = new checkPermission();


        $this->assertTrue($rule->passes('permission', [$permission->id => 'on']));
    }

    public function test_check_permission_not_valid(): void
    {
        $rule = new checkPermission();

        $this->assertFalse($rule->passes('permission', [1 => 'on']));
        $this->assertEquals('The selected permission is invalid.', $rule->message());
    }
}
