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
        if(is_null(User::where('name', 'Super Admin' )->first())) {
            $super_admin = User::factory()->create([
                'uuid' => '010127',
                'name' => 'Super Admin',
                'email' => 'superadmin@admin.com',
            ]);
            $super_admin->assignRole('super admin');
        }

       User::factory(4000)->create()->each(function ($user) {
            $user->assignRole('employee');
       });

    }
}
