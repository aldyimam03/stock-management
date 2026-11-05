<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\StockMovement;
use App\Models\Warehouse;
use App\Models\Supplier;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<StockMovement>
 */
class StockMovementFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'product_id' => Product::factory(),
            'warehouse_id' => Warehouse::factory(),
            'supplier_id' => Supplier::factory(),
            'customer_id' => null,
            'type' => fake()->randomElement(['in', 'out', 'adjustment', 'transfer', 'return']),
            'quantity' => fake()->numberBetween(1, 100),
            'unit_price' => fake()->randomFloat(2, 10000, 1000000),
            'total_value' => function (array $attributes) {
                return $attributes['quantity'] * $attributes['unit_price'];
            },
            'note' => fake()->optional()->sentence(),
            'created_by' => null,
        ];
    }
}