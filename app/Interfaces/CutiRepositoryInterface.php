<?php

namespace App\Interfaces;

use App\Http\Requests\StoreCutiRequest;
use Illuminate\Support\Collection;

interface CutiRepositoryInterface
{
    public function getCuti(): Collection;

    public function store(array $request): void;

    public function update(array $request, $id): void;

    public function delete($id): void;
}
