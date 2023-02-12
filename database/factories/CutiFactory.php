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
            'from' => $this->faker->date(),
            'to' => $this->faker->date(),
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
