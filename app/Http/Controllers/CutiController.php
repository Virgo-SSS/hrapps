<?php

namespace App\Http\Controllers;

use App\Interfaces\CutiRepositoryInterface;
use App\Models\Cuti;
use App\Http\Requests\StoreCutiRequest;
use App\Http\Requests\UpdateCutiRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\View\View;

class CutiController extends Controller
{
    private CutiRepositoryInterface $repository;

    public function __construct(CutiRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function index(): View
    {
        $cutis = $this->repository->getCuti();
        return view('cuti.index', compact('cutis'));
    }

    public function create(): View
    {
        $users = User::all();
        return view('cuti.create', compact('users'));
    }

    public function request()
    {
        $pendingCutis = $this->repository->getPendingCuti();
        return view('cuti.request', compact('pendingCutis'));
    }

    public function store(StoreCutiRequest $request): RedirectResponse
    {
        $this->repository->store($request->all());
        return redirect()->route('cuti.index')->with('toastr-success', 'Cuti created successfully.');
    }

    public function show(Cuti $cuti): View
    {
        return view('cuti.detail', compact('cuti'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Cuti  $cuti
     * @return \Illuminate\Http\Response
     */
    public function edit(Cuti $cuti)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateCutiRequest  $request
     * @param  \App\Models\Cuti  $cuti
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCutiRequest $request, Cuti $cuti)
    {
        //
    }

    public function destroy(Cuti $cuti): RedirectResponse
    {
        $this->repository->delete($cuti->id);
        return redirect()->route('cuti.index')->with('toastr-success', 'Cuti deleted successfully.');
    }
}
