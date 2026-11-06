<?php

namespace Database\Factories;

use App\Models\UnitOfMeasure;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<UnitOfMeasure>
 */
class UnitOfMeasureFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $names = ['Unit', 'Lembar', 'Kg', 'L', 'Box', 'Pack'];
        $symbols = ['pcs', 'lbr', 'kg', 'l', 'box', 'pck'];

        return [
            'code' => $this->faker->unique()->lexify('UOM###'),
            'name' => $this->faker->randomElement($names),
            'symbol' => $this->faker->randomElement($symbols),
            'description' => $this->faker->optional()->sentence(),
            'is_active' => true,
        ];
    }
}
