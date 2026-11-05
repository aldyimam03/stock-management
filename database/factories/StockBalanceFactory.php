<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\StockBalance;
use App\Models\Warehouse;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<StockBalance>
 */
class StockBalanceFactory extends Factory
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
            'quantity' => fake()->numberBetween(0, 1000),
            'reserved_quantity' => fake()->numberBetween(0, 50),
            'average_cost' => fake()->randomFloat(2, 5000, 500000),
        ];
    }
}