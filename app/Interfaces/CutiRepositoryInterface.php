<?php

namespace App\Interfaces;

use Illuminate\Support\Collection;

interface CutiRepositoryInterface
{
    public function getCuti(): Collection;

    public function create(array $data): void;

    public function update(array $data, $id): void;

    public function delete($id): void;
}
