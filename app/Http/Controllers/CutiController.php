<?php

namespace App\Http\Controllers;

use App\Exceptions\CutiDateRequestedException;
use App\Exceptions\CutiRequestStillProcessingException;
use App\Http\Requests\ActionLeaveRequest;
use App\Interfaces\CutiRepositoryInterface;
use App\Models\Cuti;
use App\Http\Requests\StoreCutiRequest;
use App\Http\Requests\UpdateCutiRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
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

    public function pending(): view
    {
        abort_if(!Gate::allows('view cuti request'), 403);

        $pendingCutis = $this->repository->getPendingCuti();
        return view('cuti.pending', compact('pendingCutis'));
    }

    public function store(StoreCutiRequest $request): RedirectResponse
    {
        try {
            abort_if(!Gate::allows('create cuti'), 403);

            $this->repository->store($request->all());
            return redirect()->route('cuti.index')->with('toastr-success', 'Cuti created successfully.');
        } catch (CutiRequestStillProcessingException $e) {
            return redirect()->back()->with('swal-warning', $e->getMessage());
        } catch (CutiDateRequestedException $e) {
            return redirect()->back()->with('swal-error', $e->getMessage());
        }
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

        $this->repository->update($request->validated(), $cuti);

        return redirect()->route('cuti.edit', $cuti->id)->with('toastr-success', 'Cuti updated successfully.');
    }

    public function destroy(Cuti $cuti): RedirectResponse
    {
        abort_if(!Gate::allows('delete cuti'), 403);

        $this->repository->delete($cuti);
        return redirect()->route('cuti.index')->with('toastr-success', 'Cuti deleted successfully.');
    }

    public function approve(Cuti $cuti, ActionLeaveRequest $request): RedirectResponse
    {
        abort_if(!Gate::allows('view cuti request'), 403);

        $this->repository->processStatus($cuti, $request->only(['note', 'status']));
        return redirect()->route('cuti.pending')->with('toastr-success', 'Cuti approved successfully.');
    }

    public function reject(Cuti $cuti,  ActionLeaveRequest $request): RedirectResponse
    {
        abort_if(!Gate::allows('view cuti request'), 403);

        $this->repository->processStatus($cuti, $request->only(['note', 'status']));
        return redirect()->route('cuti.pending')->with('toastr-success', 'Cuti rejected successfully.');
    }
}
