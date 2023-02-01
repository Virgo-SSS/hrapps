<?php

namespace App\Repository;

use App\Http\Requests\StoreDivisiRequest;
use App\Http\Requests\UpdateDivisiRequest;
use App\Models\Divisi;
use App\Interfaces\DivisiRepositoryInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class DivisiRepository implements DivisiRepositoryInterface
{
    public function getDivisi(): Collection
    {
        return Divisi::with(['createdBy', 'editedBy'])->get();
    }

    public function store(StoreDivisiRequest $request): void
    {
        Divisi::create([
            'name' => strtolower($request->name),
            'created_by' => Auth::id(),
        ]);
    }

    public function update(UpdateDivisiRequest $request, Divisi $divisi): void
    {
        $divisi->update([
            'name' => strtolower($request->name),
            'is_active' => $request->is_active,
            'edited_by' => Auth::id(),
        ]);
    }

    public function delete(Divisi $divisi): void
    {
        $divisi->delete();
    }
}
