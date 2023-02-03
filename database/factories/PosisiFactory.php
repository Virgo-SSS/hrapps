<?php

namespace Database\Factories;

use App\Models\Divisi;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Posisi>
 */
class PosisiFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'divisi_id' => $this->getDivisiId(),
            'is_active' => $this->faker->boolean,
        ];
    }

    private function getDivisiId()
    {
        return Divisi::factory()->create()->id;
    }
}
