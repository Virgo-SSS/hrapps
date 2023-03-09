<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Cuti>
 */
class CutiFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'user_id' => $this->getUserId(),
            'from' => $this->faker->dateTimeBetween('now', '+1 year')->format('Y-m-d'),
            'to' => $this->faker->dateTimeBetween('+1 year', '+2 year')->format('Y-m-d'),
            'reason' => $this->faker->text(),
        ];
    }

    private function getUserId(): int
    {
        $user = User::inRandomOrder()->first();
        if($user) {
            return $user->id;
        } else {
            return User::factory()->create()->id;
        }
    }
}
