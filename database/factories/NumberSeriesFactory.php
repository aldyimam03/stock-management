<?php

namespace Database\Factories;

use App\Models\NumberSeries;
use Illuminate\Database\Eloquent\Factories\Factory;

class NumberSeriesFactory extends Factory
{
    public function definition(): array
    {
        $codes = ['PO', 'SO', 'GR', 'SH'];
        $names = ['Purchase Order', 'Sales Order', 'Goods Receipt', 'Shipment'];

        return [
            'code' => $this->faker->randomElement($codes),
            'name' => $this->faker->randomElement($names),
            'prefix' => null,
            'suffix' => null,
            'next_number' => 1,
            'is_active' => true,
        ];
    }
}