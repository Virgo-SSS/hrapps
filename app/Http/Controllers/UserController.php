<?php

namespace App\Http\Controllers;

use App\Interfaces\DivisiRepositoryInterface;
use App\Interfaces\PosisiRepositoryInterface;
use App\Interfaces\UserRepositoryInterface;
use App\Models\User;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class UserController extends Controller
{
    private $repository;
    private $divisiRepository;

    public function __construct(UserRepositoryInterface $repository, DivisiRepositoryInterface $divisiRepository)
    {
        $this->repository = $repository;
        $this->divisiRepository = $divisiRepository;
    }

    public function index(): View
    {
        abort_if(!Gate::allows('view user'), 403);

        $users = $this->repository->getUser();
        return view('users.index', compact('users'));
    }

    public function create(): View
    {
        abort_if(!Gate::allows('create user'), 403);

        $divisis = $this->divisiRepository->getDivisiWithoutEagerLoading();
        return view('users.create', compact('divisis' ));
    }

    public function store(StoreUserRequest $request): RedirectResponse
    {
        abort_if(!Gate::allows('create user'), 403);

        $this->repository->create($request->validated());
        return redirect()->route('users.index')->with('toastr-success', 'User Successfully Added');
    }

    public function edit(User $user): View
    {
        abort_if(!Gate::allows('edit user'), 403);

        $divisis = $this->divisiRepository->getDivisiWithoutEagerLoading();
        return view('users.edit', compact('user', 'divisis'));
    }

    public function update(UpdateUserRequest $request, User $user): RedirectResponse
    {
        abort_if(!Gate::allows('edit user'), 403);

        $this->repository->update($request->validated(), $user);
        return redirect()->route('users.index')->with('toastr-success', 'User Successfully Updated');
    }

    public function destroy(User $user): RedirectResponse
    {
        abort_if(!Gate::allows('delete user'), 403);
        
        $this->repository->delete($user);
        return redirect()->route('users.index')->with('toastr-success', 'User Successfully Deleted');
    }
}
