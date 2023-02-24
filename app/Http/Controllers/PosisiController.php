<?php

namespace App\Http\Controllers;

use App\Interfaces\DivisiRepositoryInterface;
use App\Interfaces\PosisiRepositoryInterface;
use App\Models\Posisi;
use App\Http\Requests\StorePosisiRequest;
use App\Http\Requests\UpdatePosisiRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;
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
        abort_if(!Gate::allows('view posisi'), 403);

        $posisis = $this->repository->getPosisi();
        $divisis = $this->divisiRepository->getDivisiWithoutEagerLoading();
        return view('posisi.index', compact('posisis','divisis'));
    }

    public function store(StorePosisiRequest $request): RedirectResponse
    {
        abort_if(!Gate::allows('create posisi'), 403);

        $this->repository->store($request->validated());
        return redirect()->route('posisi.index')->with('toastr-success', 'Posisi Successfully Created');
    }

    public function edit(Posisi $posisi): View
    {
        abort_if(!Gate::allows('edit posisi'), 403);

        $divisis = $this->divisiRepository->getDivisiWithoutEagerLoading();
        return view('posisi.edit', compact('posisi','divisis'));
    }

    public function update(UpdatePosisiRequest $request, Posisi $posisi): RedirectResponse
    {
        abort_if(!Gate::allows('edit posisi'), 403);

        $this->repository->update($request->validated(), $posisi);
        return redirect()->route('posisi.index')->with('toastr-success', 'Posisi Successfully Updated');
    }

    public function destroy(Posisi $posisi): RedirectResponse
    {
        abort_if(!Gate::allows('delete posisi'), 403);

        $this->repository->delete($posisi);
        return redirect()->route('posisi.index')->with('toastr-success', 'Posisi Successfully Deleted');
    }

    public function getPosisiByDivisi(int $divisi_id): JsonResponse
    {
        abort_if(!Gate::allows('view posisi'), 403);
        $posisis = $this->repository->getPosisiByDivisi($divisi_id);
        return response()->json($posisis);
    }
}
