<?php

namespace Database\Seeders;

use App\Models\StockMovement;
use App\Models\Product;
use App\Models\Warehouse;
use App\Models\User;
use Illuminate\Database\Seeder;

class StockMovementSeeder extends Seeder
{
    public function run(): void
    {
        $products = Product::where('is_active', true)->get();
        $warehouses = Warehouse::where('is_active', true)->get();
        $users = User::all();

        if ($products->isEmpty() || $warehouses->isEmpty()) {
            return;
        }

        $types = ['in', 'out', 'adjustment'];

        foreach (range(1, 20) as $i) {
            $product = $products->random();
            $warehouse = $warehouses->random();

            $type = $types[array_rand($types)];
            $quantity = rand(1, 50);
            $unitPrice = rand(10000, 1000000);

            StockMovement::create([
                'product_id' => $product->id,
                'warehouse_id' => $warehouse->id,
                'type' => $type,
                'quantity' => $quantity,
                'unit_price' => $unitPrice,
                'total_value' => $quantity * $unitPrice,
                'note' => 'Stok ' . ($type === 'in' ? 'masuk' : ($type === 'out' ? 'keluar' : 'penyesuaian')),
                'created_by' => $users->random()->id,
            ]);
        }
    }
}