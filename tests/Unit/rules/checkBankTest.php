<?php

namespace Tests\Unit\rules;

use App\Rules\CheckBank;
use Tests\TestCase;


class checkBankTest extends TestCase
{
    public function test_bank_code_available_in_config(): void
    {
        $rule = new CheckBank();

        $this->assertTrue($rule->passes('bank', 1));
    }

    public function test_bank_code_not_available_in_config(): void
    {
        $rule = new CheckBank();

        $this->assertFalse($rule->passes('bank', 99999999));
    }
}
