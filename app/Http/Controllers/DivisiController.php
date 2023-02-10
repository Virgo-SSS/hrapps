<?php

namespace App\Http\Controllers;

use App\Interfaces\DivisiRepositoryInterface;
use App\Models\Divisi;
use App\Http\Requests\StoreDivisiRequest;
use App\Http\Requests\UpdateDivisiRequest;
use Illuminate\Http\RedirectResponse;
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
        $divisis = $this->repository->getDivisi();
        return view('divisi.index', compact('divisis'));
    }

    public function create()
    {
        //
    }

    public function store(StoreDivisiRequest $request): RedirectResponse
    {
        $this->repository->store($request->validated());
        return redirect()->route('divisi.index')->with('toastr-success', 'Divisi Successfully Created');
    }

    public function show(Divisi $divisi)
    {
        //
    }

    public function edit(Divisi $divisi): View
    {
        return view('divisi.edit', compact('divisi'));
    }

    public function update(UpdateDivisiRequest $request, Divisi $divisi): RedirectResponse
    {
        $this->repository->update($request->validated(), $divisi);
        return redirect()->route('divisi.index')->with('toastr-success', 'Divisi Successfully Updated');
    }

    public function destroy(Divisi $divisi): RedirectResponse
    {
        $this->repository->delete($divisi);
        return redirect()->route('divisi.index')->with('toastr-success', 'Divisi Successfully Deleted');
    }
}
