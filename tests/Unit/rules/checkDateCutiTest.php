<?php

namespace Tests\Unit\rules;

use App\Rules\CheckDateCuti;
use Carbon\Carbon;
use Tests\TestCase;

class checkDateCutiTest extends TestCase
{
    public function test_check_date_cuti_leave_date_not_valid(): void
    {
        $rule = new CheckDateCuti();

        $this->assertFalse($rule->passes('date', '2021-10-01 00:00:00 - 2021-09-01 00:00:00'));
    }

    public function test_check_date_cuti_grater_than_today(): void
    {
        $rule = new CheckDateCuti();

        $this->assertFalse($rule->passes('date',  Carbon::now()->subDays(2)->format('Y-m-d') . ' - '. Carbon::now()->addDays(2)->format('Y-m-d')));
    }

    public function test_check_date_cuti_leave_date_valid(): void
    {
        $rule = new CheckDateCuti();

        $this->assertTrue($rule->passes('date',  Carbon::now()->addDays(2)->format('Y-m-d') . ' - ' . Carbon::now()->addDays(4)->format('Y-m-d')));
    }
}
