<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePermissionRequest;
use App\Http\Requests\UpdatePermissionRequest;
use App\Interfaces\PermissionRepositoryInterface;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    private PermissionRepositoryInterface $repository;

    public function __construct(PermissionRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function index(): View
    {
        abort_if(!Gate::allows('view permission'), 403);

        $permissions = $this->repository->getAllPermissions();
        return view('role.permission', compact('permissions'));
    }

    public function store(StorePermissionRequest $request): RedirectResponse
    {
        abort_if(!Gate::allows('create permission'), 403);

        $this->repository->createPermission($request->validated());
        return redirect()->route('permission.index')->with('toastr-success', 'Permission created successfully');
    }

    public function update(UpdatePermissionRequest $request, Permission $permission): RedirectResponse
    {
        abort_if(!Gate::allows('edit permission'), 403);

        $this->repository->update($request->validated(), $permission);
        return redirect()->route('permission.index')->with('toastr-success', 'Permission updated successfully');
    }

    public function destroy(Permission $permission): RedirectResponse
    {
        abort_if(!Gate::allows('delete permission'), 403);

        $this->repository->delete($permission);
        return redirect()->route('permission.index')->with('toastr-success', 'Permission deleted successfully');
    }
}
