<?php

namespace Database\Factories;

use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Customer>
 */
class CustomerFactory extends Factory
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
            'code' => 'CST-' . strtoupper(fake()->unique()->lexify('???')),
            'email' => fake()->unique()->companyEmail(),
            'phone' => fake()->phoneNumber(),
            'address' => fake()->streetAddress(),
            'city' => fake()->city(),
            'state' => fake()->state(),
            'postal_code' => fake()->postcode(),
            'country' => 'ID',
            'credit_limit' => fake()->numberBetween(10000000, 500000000),
            'balance' => 0,
            'notes' => fake()->optional()->paragraph(),
            'is_active' => true,
        ];
    }
}