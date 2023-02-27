<?php

namespace App\Repository;

use App\Http\Requests\StoreCutiRequest;
use App\Interfaces\CutiRepositoryInterface;
use App\Interfaces\CutiRequestRepositoryInterface;
use App\Models\Cuti;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class CutiRepository implements CutiRepositoryInterface
{
    public function getCuti(): Collection
    {
        return Cuti::all();
    }

    public function getPendingCuti(): Collection
    {
        return Cuti::Pending()->get();
    }

    public function store(array $request): void
    {
        DB::transaction(function () use ($request) {
            $date = explode(' ', $request['date']);
            $cuti = auth()->user()->cuti()->create([
                'from' => $date[0],
                'to' => $date[2],
                'reason' => $request['reason'],
            ]);

            app(CutiRequestRepositoryInterface::class)->create($cuti, $request);
        });
    }

    public function update(array $request, $id): void
    {
        Cuti::find($id)->update($request);
    }

    public function delete(int $id): void
    {
        Cuti::find($id)->delete();
    }
}
