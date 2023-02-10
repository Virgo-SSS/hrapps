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

    public function getPosisiByDivisi(int $divisi_id): Collection;

    public function store(array $request): void;

    public function update(array $request, Posisi $posisi): void;

    public function delete(Posisi $posisi): void;
}
