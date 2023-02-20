<?php

namespace App\Repository;

use App\Interfaces\RoleRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Collection;

class RoleRepository implements RoleRepositoryInterface
{
    public function getAllRoles(): collection
    {
        return Role::all();
    }

    public function create(array $data): Void
    {
        DB::transaction(function () use ($data) {
            $role = Role::create(['name' => $data['name']]);

            if (isset($data['permission'])) {
                $id = [];
                foreach ($data['permission'] as $key => $value) {
                    $id[] = $key;
                }
                Permission::whereIN('id', $id)->get()->map(function ($permission) use ($role) {
                    $role->givePermissionTo($permission->name);
                });
            }
        });

    }

    public function update(array $data, Role $role): Void
    {
        DB::transaction(function () use ($data, $role) {
            $role->update(['name' => $data['name']]);

            if (isset($data['permission'])) {
                $id = [];
                foreach ($data['permission'] as $key => $value) {
                    $id[] = $key;
                }
                $permission = Permission::whereIN('id', $id)->get();
                $role->syncPermissions($permission);
            }
        });
    }
}

