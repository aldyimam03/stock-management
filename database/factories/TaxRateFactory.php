<?php

namespace Database\Factories;

use App\Models\TaxRate;
use Illuminate\Database\Eloquent\Factories\Factory;

class TaxRateFactory extends Factory
{
    public function definition(): array
    {
        return [
            'code' => strtoupper($this->faker->lexify('TX###')),
            'name' => $this->faker->randomElement(['PPN 11%', 'PPN 12%', 'PPH 23']),
            'rate' => $this->faker->randomElement([11.00, 12.00, 2.00, 0.00]),
            'type' => 'percent',
            'is_active' => true,
        ];
    }
}