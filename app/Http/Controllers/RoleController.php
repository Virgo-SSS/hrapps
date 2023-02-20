<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRoleRequest;
use App\Http\Requests\UpdateRoleRequest;
use App\Interfaces\PermissionRepositoryInterface;
use App\Interfaces\RoleRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Gate;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    private RoleRepositoryInterface $repository;
    private PermissionRepositoryInterface $permissionRepository;

    public function __construct(RoleRepositoryInterface $repository, PermissionRepositoryInterface $permissionRepository)
    {
        $this->repository = $repository;
        $this->permissionRepository = $permissionRepository;
    }

    public function index(): View
    {
        abort_if(!Gate::allows('view role'), 403);

        $roles = $this->repository->getAllRoles();
        return view('role.index', compact('roles'));
    }

    public function create(): View
    {
        abort_if(!Gate::allows('create role'), 403);

        $permissions = $this->permissionRepository->getAllPermissions();
        return view('role.create', compact('permissions'));
    }

    public function store(StoreRoleRequest $request): RedirectResponse
    {
        abort_if(!Gate::allows('create role'), 403);

        $this->repository->create($request->all());
        return redirect()->route('role.index')->with('toastr-success', 'Role created successfully.');
    }

    public function edit(Role $role): View
    {
        abort_if(!Gate::allows('edit role'), 403);

        $role->load('permissions');

        $permissions = $this->permissionRepository->getAllPermissions();
        return view('role.edit', compact('role', 'permissions'));
    }

    public function update(UpdateRoleRequest $request, Role $role): RedirectResponse
    {
        abort_if(!Gate::allows('edit role'), 403);

        $this->repository->update($request->all(), $role);
        return redirect()->route('role.index')->with('toastr-success', 'Role updated successfully.');
    }

    public function destroy(Role $role): RedirectResponse
    {
        abort_if(!Gate::allows('delete role'), 403);

        $role->delete();
        return redirect()->route('role.index')->with('toastr-success', 'Role deleted successfully.');
    }

    public function permissions(Role $role): JsonResponse
    {
        abort_if(!Gate::allows('view role'), 403);

        $permissions = $role->permissions()->get(['name']);
        return response()->json(['data' => $permissions]);
    }
}
