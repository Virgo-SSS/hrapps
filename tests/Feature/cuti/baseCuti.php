<?php

namespace Tests\Feature\cuti;

use App\Models\Cuti;
use App\Models\User;
use Tests\TestCase;

abstract class baseCuti extends TestCase
{
    public function prepareRequest(): array
    {
        $request = Cuti::factory()->make()->toArray();
        $request['date'] = $request['from'] . ' - ' . $request['to'];
        $request['head_of_division'] = User::factory()->create()->id;
        $request['head_of_department'] = User::factory()->create()->id;

        unset($request['user_id']);
        unset($request['from']);
        unset($request['to']);
        return $request;
    }
}
