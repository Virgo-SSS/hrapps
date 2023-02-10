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

    public function getPosisiByDivisi(int $divisi_id): Collection
    {
        return Posisi::where('divisi_id', $divisi_id)->get();
    }

    public function store(array $request): void
    {
        Posisi::create($request);
    }

    public function update(array $request, Posisi $posisi): void
    {
        $posisi->update($request);
    }

    public function delete(Posisi $posisi): void
    {
        $posisi->delete();
    }
}

