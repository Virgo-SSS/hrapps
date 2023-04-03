<?php

namespace Tests\Feature\cuti;

use App\Models\Cuti;
use App\Models\User;
use Tests\TestCase;

abstract class baseCuti extends TestCase
{
    protected function prepareRequest(): array
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

    protected function userEdit(): array
    {
        return [
            'super admin' => ['super admin'],
            'employee' => ['employee', 'edit cuti'],
        ];
    }

    protected function userCreate(): array
    {
        return [
            'super admin' => ['super admin'],
            'employee' => ['employee', 'create cuti'],
        ];
    }

    protected function userDelete(): array
    {
        return [
            'super admin' => ['super admin'],
            'employee' => ['employee', 'delete cuti'],
        ];
    }

    protected function userIndex(): array
    {
        return [
            'super admin' => ['super admin'],
            'employee' => ['employee', 'view cuti'],
        ];
    }

    protected function userViewCutiReqeust(): array
    {
        return [
            'super admin' => ['super admin'],
            'employee' => ['employee', 'view cuti request'],
        ];
    }
}
