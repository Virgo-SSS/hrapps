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
    private int $divisi_id;

    private int $posisi_id;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $this->setDivisiAndPosisi();

        return [
            'user_id'             => $this->getUserId(),
            'divisi_id'           => $this->divisi_id,
            'posisi_id'           => $this->posisi_id,
            'bank'                => rand(1,6),
            'bank_account_number' => '1234567890',
            'join_date'           => $this->faker->date(),
            'cuti'                => rand(10,30),
            'salary'              => rand(1000000, 5000000),
        ];
    }

    private function setDivisiAndPosisi(): void
    {
        $posisi = Posisi::inRandomOrder()->first();
        if($posisi) {
            $this->divisi_id = $posisi->divisi_id;
            $this->posisi_id = $posisi->id;
        }else {
            $posisi = Posisi::factory()->create();
            $this->divisi_id = $posisi->divisi_id;
            $this->posisi_id = $posisi->id;
        }
    }

    private function getUserId(): int
    {
        return User::factory()->create()->id;
    }
}
