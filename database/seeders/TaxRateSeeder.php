<?php

namespace Database\Seeders;

use App\Models\TaxRate;
use Illuminate\Database\Seeder;

class TaxRateSeeder extends Seeder
{
    public function run(): void
    {
        $taxRates = [
            ['code' => 'PPN11', 'name' => 'PPN 11%', 'rate' => 11.00, 'type' => 'percent', 'is_active' => true],
            ['code' => 'PPN12', 'name' => 'PPN 12%', 'rate' => 12.00, 'type' => 'percent', 'is_active' => false],
            ['code' => 'PPH23', 'name' => 'PPH 23%', 'rate' => 2.00, 'type' => 'percent', 'is_active' => true],
            ['code' => 'NON', 'name' => 'Non Tax', 'rate' => 0.00, 'type' => 'percent', 'is_active' => true],
        ];

        foreach ($taxRates as $tax) {
            TaxRate::firstOrCreate(
                ['code' => $tax['code']],
                $tax
            );
        }
    }
}