<?php

namespace App\Interfaces;

use App\Http\Requests\UpdateDivisiRequest;
use App\Models\Divisi;
use Illuminate\Support\Collection;
use App\Http\Requests\StoreDivisiRequest;

interface DivisiRepositoryInterface
{
    public function getDivisi(): Collection;

    public function getDivisiWithoutEagerLoading(): Collection;

    public function store(array $request): void;

    public function update(array $request, Divisi $divisi): void;

    public function delete(Divisi $divisi): void;
}
