<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permission = [
            'view permission',
            'create permission',
            'edit permission',
            'delete permission',
            'view role',
            'create role',
            'edit role',
            'delete role',
            'view user',
            'create user',
            'edit user',
            'delete user',
            'view divisi',
            'create divisi',
            'edit divisi',
            'delete divisi',
        ];

        foreach ($permission as $key => $value) {
            Permission::create(['name' => $value]);
        }
    }
}
