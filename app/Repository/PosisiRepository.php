<?php

namespace App\Repository;

use App\Http\Requests\StorePosisiRequest;
use App\Http\Requests\UpdatePosisiRequest;
use App\Interfaces\PosisiRepositoryInterface;
use App\Models\Posisi;
use Illuminate\Support\Collection;

class PosisiRepository implements PosisiRepositoryInterface
{
    public function getPosisi(): Collection
    {
        return Posisi::with(['divisi'])->get();
    }

    public function store(StorePosisiRequest $request): void
    {
        Posisi::create($request->validated());
    }

    public function update(UpdatePosisiRequest $request, Posisi $posisi): void
    {
        $posisi->update($request->validated());
    }

    public function delete(Posisi $posisi): void
    {
        $posisi->delete();
    }
}

