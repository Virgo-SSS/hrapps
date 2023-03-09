<?php

namespace App\Interfaces;

use App\Models\Cuti;

interface CutiRequestRepositoryInterface
{
    public function create(Cuti $cuti,array $data): void;

    public function update(Cuti $cuti, array $data): void;
}
