<?php

namespace App\Http\Controllers;

use App\Interfaces\DivisiRepositoryInterface;
use App\Interfaces\PosisiRepositoryInterface;
use App\Interfaces\UserRepositoryInterface;
use App\Models\User;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Js;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;

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

        return view('users.index');
    }

    public function getUserDataInJson(): JsonResponse
    {
        abort_if(!Gate::allows('view user'), 403);

        $users = $this->repository->getUser();
        return datatables()->of($users)
            ->addIndexColumn()
            ->addColumn('action', function ($user) {
                $editUrl = route('users.edit', $user->id);
                $deleteUrl = route('users.destroy', $user->id);
                $csrfToken = csrf_token();
                return <<<HTML
                        <a href="{$editUrl}" title="Edit user">
                            <i class="fa fa-pencil"></i>
                        </a> |
                        <a href="#" onclick="event.preventDefault();deleteItem('#deleteUser-{$user->id}', '{$user->name}')" title="Delete user">
                            <i class="fa fa-trash" style="color: red;"></i>
                        </a>
                        <form action="{$deleteUrl} " method="POST" id="deleteUser-{$user->id}" style="display: inline-block;">
                            <input type="hidden" name="_token" value="{$csrfToken}">
                            <input type="hidden" name="_method" value="DELETE">
                        </form>
                    HTML;
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function create(): View
    {
        abort_if(!Gate::allows('create user'), 403);

        $divisis = $this->divisiRepository->getDivisiWithoutEagerLoading();
        $roles = Role::all();
        return view('users.create', compact('divisis', 'roles'));
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
        $roles = Role::all();
        return view('users.edit', compact('user', 'divisis', 'roles'));
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
