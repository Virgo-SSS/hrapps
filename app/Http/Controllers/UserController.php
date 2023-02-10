<?php

namespace App\Http\Controllers;

use App\Interfaces\DivisiRepositoryInterface;
use App\Interfaces\PosisiRepositoryInterface;
use App\Interfaces\UserRepositoryInterface;
use App\Models\User;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use Illuminate\Http\RedirectResponse;
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
        $users = $this->repository->getUser();
        return view('users.index', compact('users'));
    }

    public function create(): View
    {
        $divisis = $this->divisiRepository->getDivisiWithoutEagerLoading();
        return view('users.create', compact('divisis' ));
    }

    public function store(StoreUserRequest $request): RedirectResponse
    {
        $this->repository->create($request->validated());
        return redirect()->route('users.index')->with('toastr-success', 'User Successfully Added');
    }

    public function show(User $user)
    {
        //
    }

    public function edit(User $user): View
    {
        $divisis = $this->divisiRepository->getDivisiWithoutEagerLoading();
        return view('users.edit', compact('user', 'divisis'));
    }

    public function update(UpdateUserRequest $request, User $user): RedirectResponse
    {
        $this->repository->update($request->validated(), $user);
        return redirect()->route('users.index')->with('toastr-success', 'User Successfully Updated');
    }

    public function destroy(User $user): RedirectResponse
    {
        $this->repository->delete($user);
        return redirect()->route('users.index')->with('toastr-success', 'User Successfully Deleted');
    }
}
