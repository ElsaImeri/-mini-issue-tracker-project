<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class TagFactory extends Factory
{
    public function definition(): array
    {
        $colors = ['#FF5733', '#33FF57', '#3357FF', '#F3FF33', '#FF33F3', '#33FFF3'];
        
        return [
            'name' => $this->faker->unique()->word(),
            'color' => $this->faker->randomElement($colors),
        ];
    }
}