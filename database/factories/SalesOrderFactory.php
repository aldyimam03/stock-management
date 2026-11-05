<?php

namespace Database\Factories;

use App\Models\SalesOrder;
use App\Models\Customer;
use App\Models\Warehouse;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<SalesOrder>
 */
class SalesOrderFactory extends Factory
{
    public function definition(): array
    {
        return [
            'customer_id' => Customer::factory(),
            'warehouse_id' => Warehouse::factory(),
            'order_date' => fake()->date(),
            'expected_delivery_date' => fake()->date(),
            'status' => fake()->randomElement(['draft', 'pending', 'confirmed', 'shipped', 'delivered', 'cancelled']),
            'total_amount' => fake()->randomFloat(2, 1000000, 100000000),
            'created_by' => User::factory(),
            'notes' => fake()->optional()->paragraph(),
        ];
    }
}
