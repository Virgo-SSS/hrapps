<?php

namespace Tests\Unit\rules;

use App\Rules\CheckDateFormat;
use PHPUnit\Framework\TestCase;

class checkDateFormatTest extends TestCase
{
    public function test_daterange_format_ymd_is_not_valid(): void
    {
        $rule = new CheckDateFormat('Y-m-d', 'daterange');

        $this->assertFalse((bool) $rule->passes('date', '01-01-2020 - 01-02-2020'));
    }

    public function test_daterange_format_ymd_is_valid(): void
    {
        $rule = new CheckDateFormat('Y-m-d', 'daterange');

        $this->assertTrue((bool) $rule->passes('date', '2020-01-01 - 2020-01-02'));
    }
}
