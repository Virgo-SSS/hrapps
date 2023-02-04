<?php

namespace Database\Factories;

use App\Models\Divisi;
use App\Models\Posisi;
use App\Models\User;
use App\Rules\CheckBank;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\UserProfile>
 */
class UserProfileFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'user_id'             => $this->getUserId(),
            'divisi_id'           => $this->getDivisiId(),
            'posisi_id'           => $this->getPosisiId(),
            'bank'                => rand(1,6),
            'bank_account_number' => '1234567890',
            'join_date'           => $this->faker->date(),
            'cuti'                => rand(10,30),
            'salary'              => rand(1000000, 5000000),
        ];
    }

    private function getUserId()
    {
        return User::factory()->create()->id;
    }

    private function getDivisiId()
    {
        return Divisi::factory()->create()->id;
    }

    private function getPosisiId()
    {
        return Posisi::factory()->create()->id;
    }
}
