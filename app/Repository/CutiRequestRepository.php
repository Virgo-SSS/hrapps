<?php

namespace App\Repository;

use App\Interfaces\CutiRequestRepositoryInterface;
use App\Models\Cuti;
use App\Models\CutiRequest;

class CutiRequestRepository implements CutiRequestRepositoryInterface
{
    public function create(Cuti $cuti, array $data): void
    {
        CutiRequest::create([
            'cuti_id' => $cuti->id,
            'head_of_division' => $data['head_of_division'],
            'head_of_department' => $data['head_of_department'],
        ]);
    }

    public function update(Cuti $cuti, array $data): void
    {
        $cuti->cutiRequest()->update([
            'head_of_division' => $data['head_of_division'],
            'head_of_department' => $data['head_of_department'],
        ]);
    }
}
