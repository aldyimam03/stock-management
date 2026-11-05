<?php

namespace Database\Factories;

use App\Models\Warehouse;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Warehouse>
 */
class WarehouseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->company() . ' Warehouse';
        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'code' => 'WH-' . strtoupper(fake()->unique()->lexify('???')),
            'address' => fake()->streetAddress(),
            'city' => fake()->city(),
            'state' => fake()->state(),
            'postal_code' => fake()->postcode(),
            'country' => 'ID',
            'phone' => fake()->phoneNumber(),
            'notes' => fake()->optional()->paragraph(),
            'is_active' => true,
            'is_default' => false,
        ];
    }
}
