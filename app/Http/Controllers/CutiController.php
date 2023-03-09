<?php

namespace App\Http\Controllers;

use App\Interfaces\CutiRepositoryInterface;
use App\Models\Cuti;
use App\Http\Requests\StoreCutiRequest;
use App\Http\Requests\UpdateCutiRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class CutiController extends Controller
{
    private CutiRepositoryInterface $repository;

    public function __construct(CutiRepositoryInterface $repository)
    {
        $this->repository = $repository;
        $this->middleware('cutiEdit')->only('edit', 'update');
    }

    public function index(): View
    {
        abort_if(!Gate::allows('view cuti'), 403);

        $cutis = $this->repository->getCuti();
        return view('cuti.index', compact('cutis'));
    }

    public function create(): View
    {
        abort_if(!Gate::allows('create cuti'), 403);

        $users = User::all();
        return view('cuti.create', compact('users'));
    }

    public function request(): view
    {
        abort_if(!Gate::allows('view cuti request'), 403);

        $pendingCutis = $this->repository->getPendingCuti();
        return view('cuti.request', compact('pendingCutis'));
    }

    public function store(StoreCutiRequest $request): RedirectResponse
    {
        abort_if(!Gate::allows('create cuti'), 403);

        $this->repository->store($request->all());
        return redirect()->route('cuti.index')->with('toastr-success', 'Cuti created successfully.');
    }

    public function show(Cuti $cuti): View
    {
        abort_if(!Gate::allows('view cuti'), 403);

        return view('cuti.detail', compact('cuti'));
    }

    public function edit(Cuti $cuti): View
    {
        abort_if(!Gate::allows('edit cuti'), 403);
        $users = User::all();
        $cuti->load('cutiRequest');

        return view('cuti.edit', compact('cuti', 'users'));
    }

    public function update(UpdateCutiRequest $request, Cuti $cuti): RedirectResponse
    {
        abort_if(!Gate::allows('edit cuti'), 403);

        $this->repository->update($request->all(), $cuti);

        return redirect()->route('cuti.edit', $cuti->id)->with('toastr-success', 'Cuti updated successfully.');
    }

    public function destroy(Cuti $cuti): RedirectResponse
    {
        abort_if(!Gate::allows('delete cuti'), 403);

        $this->repository->delete($cuti->id);
        return redirect()->route('cuti.index')->with('toastr-success', 'Cuti deleted successfully.');
    }
}
