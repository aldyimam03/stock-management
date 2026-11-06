<?php

namespace Database\Seeders;

use App\Models\StockBalance;
use App\Models\Product;
use App\Models\Warehouse;
use Illuminate\Database\Seeder;

class StockBalanceSeeder extends Seeder
{
    public function run(): void
    {
        $products = Product::where('is_active', true)->get();
        $warehouses = Warehouse::where('is_active', true)->get();

        if ($products->isEmpty() || $warehouses->isEmpty()) {
            return;
        }

        foreach ($products as $product) {
            foreach ($warehouses as $warehouse) {
                $quantity = rand(10, 100);
                $reserved = rand(0, min(10, $quantity));

                StockBalance::updateOrCreate(
                    ['product_id' => $product->id, 'warehouse_id' => $warehouse->id],
                    [
                        'quantity' => $quantity,
                        'reserved_quantity' => $reserved,
                        'average_cost' => $product->cost_price * (0.9 + rand(0, 20) / 100),
                    ]
                );
            }
        }
    }
}