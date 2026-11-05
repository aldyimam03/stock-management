<?php

namespace Database\Factories;

use App\Models\Supplier;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Supplier>
 */
class SupplierFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->company();
        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'code' => 'SUP-' . strtoupper(fake()->unique()->lexify('???')),
            'email' => fake()->unique()->companyEmail(),
            'phone' => fake()->phoneNumber(),
            'address' => fake()->streetAddress(),
            'city' => fake()->city(),
            'state' => fake()->state(),
            'postal_code' => fake()->postcode(),
            'country' => 'ID',
            'notes' => fake()->optional()->paragraph(),
            'is_active' => true,
        ];
    }
}