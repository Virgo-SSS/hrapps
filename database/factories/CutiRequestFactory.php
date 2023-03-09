<?php

namespace Database\Factories;

use App\Models\Cuti;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class CutiRequestFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'cuti_id' => $this->getCutiId(),
            'head_of_division' => $this->getUserId(),
            'status_hod' => $this->faker->numberBetween(0, 2),
            'note_hod' => $this->faker->text,
            'approved_hod_at' => $this->faker->dateTime,
            'head_of_department' => $this->getUserId(),
            'status_hodp' => $this->faker->numberBetween(0, 2),
            'note_hodp' => $this->faker->text,
            'approved_hodp_at' => $this->faker->dateTime,
        ];
    }

    private function getCutiId(): int
    {
        $cuti = Cuti::inRandomOrder()->first();
        if($cuti) {
            return $cuti->id;
        } else {
            return Cuti::factory()->create()->id;
        }
    }

    private function getUserId(): int
    {
        // get random user
        $user = User::inRandomOrder()->first();

        if($user) {
            return $user->id;
        }else {
            return User::factory()->create()->id;
        }
    }
}
