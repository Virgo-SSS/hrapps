<?php

namespace Tests\Unit\rules;

use App\Models\Divisi;
use App\Models\Posisi;
use App\Rules\CheckPosisi;
use Illuminate\Http\Request;
use Tests\TestCase;

class checkPosisiTest extends TestCase
{
    public function test_check_posisi_will_return_false_if_posisi_not_in_the_right_divisi(): void
    {
        $divisi = Divisi::factory()->create();
        $posisi = Posisi::factory()->create([
            'divisi_id' => $divisi->id,
        ]);

        $request = app(Request::class);
        $request->merge(['divisi_id' => 999999]);

        $rule = new CheckPosisi();

        $this->assertFalse($rule->passes('posisi_id', $posisi->id));
    }

    public function test_check_posis_will_return_true_if_posisi_is_in_the_right_divisi(): void
    {
        $divisi = Divisi::factory()->create();
        $posisi = Posisi::factory()->create([
            'divisi_id' => $divisi->id,
        ]);

        $request = app(Request::class);
        $request->merge(['divisi_id' => $divisi->id]);

        $rule = new CheckPosisi();

        $this->assertTrue($rule->passes('posisi_id', $posisi->id));
    }
}
