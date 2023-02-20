<?php

namespace App\Repository;

use App\Interfaces\PermissionRepositoryInterface;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Collection;

class PermissionRepository implements PermissionRepositoryInterface
{
    public function getAllPermissions(): collection
    {
        return Permission::all();
    }

    public function createPermission(array $data): void
    {
        Permission::create($data);
    }

    public function update(array $data, Permission $permission): void
    {
        $permission->update($data);
    }

    public function delete(Permission $permission): void
    {
        $permission->delete();
    }
}
