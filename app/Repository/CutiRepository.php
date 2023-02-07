<?php

namespace App\Repository;

use App\Interfaces\CutiRepositoryInterface;
use App\Models\Cuti;
use Illuminate\Support\Collection;

class CutiRepository implements CutiRepositoryInterface
{
    public function getCuti(): Collection
    {
        return Cuti::all();
    }

    public function create(array $data): void
    {
        Cuti::create($data);
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
