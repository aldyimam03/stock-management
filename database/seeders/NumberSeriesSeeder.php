<?php

namespace Database\Seeders;

use App\Models\NumberSeries;
use Illuminate\Database\Seeder;

class NumberSeriesSeeder extends Seeder
{
    public function run(): void
    {
        $series = [
            ['code' => 'PO', 'name' => 'Purchase Order', 'prefix' => 'PO', 'next_number' => 1, 'is_active' => true],
            ['code' => 'SO', 'name' => 'Sales Order', 'prefix' => 'SO', 'next_number' => 1, 'is_active' => true],
            ['code' => 'GR', 'name' => 'Goods Receipt', 'prefix' => 'GR', 'next_number' => 1, 'is_active' => true],
            ['code' => 'SH', 'name' => 'Shipment', 'prefix' => 'SH', 'next_number' => 1, 'is_active' => true],
        ];

        foreach ($series as $s) {
            NumberSeries::firstOrCreate(
                ['code' => $s['code']],
                $s
            );
        }
    }
}