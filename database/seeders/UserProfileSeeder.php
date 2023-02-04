<?php

namespace Database\Seeders;

use App\Models\Divisi;
use App\Models\Posisi;
use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserProfileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::all();

        foreach($user as $u) {
            $divisi = Divisi::inRandomOrder()->first();
            $posisi = Posisi::inRandomOrder()->first();

            UserProfile::create([
                'user_id'             => $u->id,
                'divisi_id'           => $divisi->id,
                'posisi_id'           => $posisi->id,
                'bank'                => rand(1,6),
                'bank_account_number' => '1234567890',
                'join_date'           => fake()->date(),
                'cuti'                => rand(10,30),
                'salary'              => rand(100000, 5000000),
            ]);
        }
    }
}
