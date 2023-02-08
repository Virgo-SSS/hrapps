<?php

namespace App\Interfaces;

use App\Http\Requests\StoreCutiRequest;
use Illuminate\Support\Collection;

interface CutiRepositoryInterface
{
    public function getCuti(): Collection;

    public function store(StoreCutiRequest $request): void;

    public function update(array $data, $id): void;

    public function delete($id): void;
}
