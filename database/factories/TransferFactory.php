<?php

namespace Database\Factories;

use App\Models\Transfer;
use App\Models\User;
use App\Models\Warehouse;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Transfer>
 */
class TransferFactory extends Factory
{
    protected $model = Transfer::class;

    public function definition(): array
    {
        return [
            'from_warehouse_id' => Warehouse::factory(),
            'to_warehouse_id' => Warehouse::factory(),
            'status' => fake()->randomElement(['draft', 'pending', 'in_transit', 'completed']),
            'created_by' => User::factory(),
            'completed_by' => null,
            'note' => fake()->optional()->sentence(),
        ];
    }
}