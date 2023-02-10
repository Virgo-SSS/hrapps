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
    private $repository;

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

    public function request(): View
    {
        return view('cuti.request');
    }

    public function store(StoreCutiRequest $request): RedirectResponse
    {
        $this->repository->store($request->all());
        return redirect()->route('cuti.index')->with('toastr-success', 'Cuti created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Cuti  $cuti
     * @return \Illuminate\Http\Response
     */
    public function show(Cuti $cuti)
    {
        //
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

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Cuti  $cuti
     * @return \Illuminate\Http\Response
     */
    public function destroy(Cuti $cuti)
    {
        //
    }
}
