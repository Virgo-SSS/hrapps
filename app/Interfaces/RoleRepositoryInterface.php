<?php

namespace App\Interfaces;

use Illuminate\Support\Collection;
use Spatie\Permission\Models\Role;

interface RoleRepositoryInterface
{
    public function getAllRoles(): collection;

    public function create(array $data): Void;

    public function update(array $data, Role $role): Void;
}
