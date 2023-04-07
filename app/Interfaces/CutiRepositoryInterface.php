<?php

namespace App\Interfaces;

use App\Http\Requests\StoreCutiRequest;
use App\Models\Cuti;
use Illuminate\Support\Collection;

interface CutiRepositoryInterface
{
    public function getCuti(): Collection;

    public function getPendingCuti(): Collection;

    public function store(array $request): void;

    public function update(array $request, Cuti $cuti): void;

    public function delete(Cuti $cuti): void;

    public function processStatus(Cuti $cuti, array $request): void;
}
