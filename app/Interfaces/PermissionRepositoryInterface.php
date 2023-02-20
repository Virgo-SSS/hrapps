<?php

namespace App\Interfaces;

use Illuminate\Support\Collection;
use Spatie\Permission\Models\Permission;

interface PermissionRepositoryInterface
{
    public function getAllPermissions(): collection;

    public function createPermission(array $data): void;

    public function update(array $data, Permission $permission): void;

    public function delete(Permission $permission): void;
}
