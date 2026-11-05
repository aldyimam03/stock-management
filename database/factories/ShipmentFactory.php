<?php

namespace Database\Factories;

use App\Models\Shipment;
use App\Models\SalesOrder;
use App\Models\Warehouse;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Shipment>
 */
class ShipmentFactory extends Factory
{
    public function definition(): array
    {
        return [
            'sales_order_id' => SalesOrder::factory(),
            'warehouse_id' => Warehouse::factory(),
            'shipped_date' => fake()->date(),
            'delivery_date' => fake()->optional()->date(),
            'status' => fake()->randomElement(['pending', 'shipped', 'delivered', 'cancelled']),
            'notes' => fake()->optional()->paragraph(),
            'created_by' => User::factory(),
        ];
    }
}
