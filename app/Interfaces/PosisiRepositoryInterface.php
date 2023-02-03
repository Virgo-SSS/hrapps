<?php

namespace App\Interfaces;

use App\Http\Requests\StoreDivisiRequest;
use App\Http\Requests\StorePosisiRequest;
use App\Http\Requests\UpdateDivisiRequest;
use App\Http\Requests\UpdatePosisiRequest;
use App\Models\Divisi;
use App\Models\Posisi;
use Illuminate\Support\Collection;

interface PosisiRepositoryInterface
{
    public function getPosisi(): Collection;

    public function store(StorePosisiRequest $request): void;

    public function update(UpdatePosisiRequest $request, Posisi $posisi): void;

    public function delete(Posisi $posisi): void;
}
