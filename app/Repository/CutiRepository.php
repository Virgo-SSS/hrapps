<?php

namespace App\Repository;

use App\Http\Requests\StoreCutiRequest;
use App\Interfaces\CutiRepositoryInterface;
use App\Interfaces\CutiRequestRepositoryInterface;
use App\Models\Cuti;
use Illuminate\Support\Collection;

class CutiRepository implements CutiRepositoryInterface
{
    public function getCuti(): Collection
    {
        return Cuti::all();
    }

    public function store(StoreCutiRequest $request): void
    {
        $date = explode(' ', $request->date);
        $cuti = auth()->user()->cuti()->create([
            'from' => $date[0],
            'to' => $date[2],
            'reason' => $request->reason,
        ]);

        app(CutiRequestRepositoryInterface::class)->create($cuti, $request->safe()->only(['head_of_division','head_of_department']));
    }

    public function update(array $data, $id): void
    {
        Cuti::find($id)->update($data);
    }

    public function delete($id): void
    {
        Cuti::find($id)->delete();
    }
}
