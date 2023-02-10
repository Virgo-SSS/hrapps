<?php

namespace App\Http\Controllers;

use App\Interfaces\DivisiRepositoryInterface;
use App\Interfaces\PosisiRepositoryInterface;
use App\Models\Posisi;
use App\Http\Requests\StorePosisiRequest;
use App\Http\Requests\UpdatePosisiRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PosisiController extends Controller
{
    private $repository;
    private $divisiRepository;

    public function __construct(PosisiRepositoryInterface $repository, DivisiRepositoryInterface $divisiRepository)
    {
        $this->repository = $repository;
        $this->divisiRepository = $divisiRepository;
    }

    public function index(): View
    {
        $posisis = $this->repository->getPosisi();
        $divisis = $this->divisiRepository->getDivisiWithoutEagerLoading();
        return view('posisi.index', compact('posisis','divisis'));
    }

    public function create()
    {
        //
    }

    public function store(StorePosisiRequest $request): RedirectResponse
    {
        $this->repository->store($request->validated());
        return redirect()->route('posisi.index')->with('toastr-success', 'Posisi Successfully Created');
    }

    public function show(Posisi $posisi)
    {
        //
    }

    public function edit(Posisi $posisi): View
    {
        $divisis = $this->divisiRepository->getDivisiWithoutEagerLoading();
        return view('posisi.edit', compact('posisi','divisis'));
    }

    public function update(UpdatePosisiRequest $request, Posisi $posisi): RedirectResponse
    {
        $this->repository->update($request->validated(), $posisi);
        return redirect()->route('posisi.index')->with('toastr-success', 'Posisi Successfully Updated');
    }


    public function destroy(Posisi $posisi): RedirectResponse
    {
        $this->repository->delete($posisi);
        return redirect()->route('posisi.index')->with('toastr-success', 'Posisi Successfully Deleted');
    }


    public function getPosisiByDivisi(int $divisi_id): JsonResponse
    {
        $posisis = $this->repository->getPosisiByDivisi($divisi_id);
        return response()->json($posisis);
    }
}
