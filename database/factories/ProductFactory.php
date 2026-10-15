<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = trim(fake()->unique()->sentence(3), '.');
        $costPrice = fake()->randomFloat(2, 1000, 50000);

        return [
            'slug' => Str::slug($name),
            'name' => $name,
            'description' => fake()->sentence(),
            'category_id' => Category::factory(),
            'unit' => fake()->randomElement(['pcs', 'kg', 'box', 'ltr']),
            'cost_price' => $costPrice,
            'selling_price' => round($costPrice * 1.3, 2),
            'stock_quantity' => fake()->numberBetween(0, 100),
            'minimum_stock' => fake()->numberBetween(0, 10),
            'is_active' => fake()->boolean(80),
        ];
    }
}
