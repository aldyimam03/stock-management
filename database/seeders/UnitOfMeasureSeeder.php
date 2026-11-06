<?php

namespace Database\Seeders;

use App\Models\UnitOfMeasure;
use Illuminate\Database\Seeder;

class UnitOfMeasureSeeder extends Seeder
{
    public function run(): void
    {
        $uoms = [
            ['code' => 'PCS', 'name' => 'Pieces', 'symbol' => 'pcs', 'description' => 'Satuan per item'],
            ['code' => 'LBR', 'name' => 'Lembar', 'symbol' => 'lbr', 'description' => 'Satuan lembar'],
            ['code' => 'KG', 'name' => 'Kilogram', 'symbol' => 'kg', 'description' => 'Satuan berat kilogram'],
            ['code' => 'G', 'name' => 'Gram', 'symbol' => 'g', 'description' => 'Satuan berat gram'],
            ['code' => 'L', 'name' => 'Liter', 'symbol' => 'l', 'description' => 'Satuan volume liter'],
            ['code' => 'ML', 'name' => 'Mililiter', 'symbol' => 'ml', 'description' => 'Satuan volume mililiter'],
            ['code' => 'M', 'name' => 'Meter', 'symbol' => 'm', 'description' => 'Satuan panjang meter'],
            ['code' => 'CM', 'name' => 'Centimeter', 'symbol' => 'cm', 'description' => 'Satuan panjang centimeter'],
            ['code' => 'BOX', 'name' => 'Box', 'symbol' => 'box', 'description' => 'Satuan per box/kotak'],
            ['code' => 'PACK', 'name' => 'Pack', 'symbol' => 'pck', 'description' => 'Satuan per pack'],
        ];

        foreach ($uoms as $uom) {
            UnitOfMeasure::firstOrCreate(
                ['code' => $uom['code']],
                $uom
            );
        }
    }
}