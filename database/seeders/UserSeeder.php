<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $super_admin = User::factory()->create([
            'uuid' => '010129',
            'name' => 'Super Admin',
            'email' => 'superadmin@admin.com',
        ]);
        $super_admin->assignRole('super admin');


//        User::factory(1000)->create();
    }
}
