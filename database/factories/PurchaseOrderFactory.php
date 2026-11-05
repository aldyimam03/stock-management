<?php

namespace Database\Factories;

use App\Models\PurchaseOrder;
use App\Models\Supplier;
use App\Models\Warehouse;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<PurchaseOrder>
 */
class PurchaseOrderFactory extends Factory
{
    public function definition(): array
    {
        return [
            'supplier_id' => Supplier::factory(),
            'warehouse_id' => Warehouse::factory(),
            'order_date' => fake()->date(),
            'expected_date' => fake()->date(),
            'status' => fake()->randomElement(['draft', 'pending', 'confirmed', 'received', 'cancelled']),
            'total_amount' => fake()->randomFloat(2, 1000000, 100000000),
            'created_by' => User::factory(),
            'notes' => fake()->optional()->paragraph(),
        ];
    }
}
