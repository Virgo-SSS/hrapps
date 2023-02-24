<?php

namespace App\Http\Controllers;

use App\Interfaces\DivisiRepositoryInterface;
use App\Models\Divisi;
use App\Http\Requests\StoreDivisiRequest;
use App\Http\Requests\UpdateDivisiRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class DivisiController extends Controller
{
    private $repository;

    public function __construct(DivisiRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function index(): View
    {
        abort_if(!Gate::allows('view division'), 403);

        $divisis = $this->repository->getDivisi();
        return view('divisi.index', compact('divisis'));
    }

    public function store(StoreDivisiRequest $request): RedirectResponse
    {
        abort_if(!Gate::allows('create division'), 403);

        $this->repository->store($request->validated());
        return redirect()->route('divisi.index')->with('toastr-success', 'Divisi Successfully Created');
    }

    public function edit(Divisi $divisi): View
    {
        abort_if(!Gate::allows('edit division'), 403);

        return view('divisi.edit', compact('divisi'));
    }

    public function update(UpdateDivisiRequest $request, Divisi $divisi): RedirectResponse
    {
        abort_if(!Gate::allows('edit division'), 403);

        $this->repository->update($request->validated(), $divisi);
        return redirect()->route('divisi.index')->with('toastr-success', 'Divisi Successfully Updated');
    }

    public function destroy(Divisi $divisi): RedirectResponse
    {
        abort_if(!Gate::allows('delete division'), 403);

        $this->repository->delete($divisi);
        return redirect()->route('divisi.index')->with('toastr-success', 'Divisi Successfully Deleted');
    }
}
