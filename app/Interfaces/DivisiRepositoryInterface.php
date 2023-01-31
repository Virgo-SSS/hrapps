<?php

namespace App\Interfaces;

use App\Http\Requests\UpdateDivisiRequest;
use App\Models\Divisi;
use Illuminate\Support\Collection;
use App\Http\Requests\StoreDivisiRequest;

interface DivisiRepositoryInterface
{
    public function getDivisi(): Collection;

    public function store(StoreDivisiRequest $request): void;

    public function update(UpdateDivisiRequest $request, Divisi $divisi): void;
}
