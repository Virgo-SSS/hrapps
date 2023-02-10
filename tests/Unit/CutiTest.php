<?php

namespace Tests\Unit;

use App\Models\Cuti;
use PHPUnit\Framework\TestCase;

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

    public function test_get_status_in_human_returns_correct_string()
    {
        $cuti = new Cuti();

        $cuti->status = 0;
        $this->assertEquals('Pending', $cuti->status_in_human);

        $cuti->status = 1;
        $this->assertEquals('Approved', $cuti->status_in_human);

        $cuti->status = 2;
        $this->assertEquals('Rejected', $cuti->status_in_human);
    }
}
