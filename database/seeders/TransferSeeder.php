<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Transfer;
use App\Models\TransferItem;
use App\Models\User;
use App\Models\Warehouse;
use Illuminate\Database\Seeder;

class TransferSeeder extends Seeder
{
    public function run(): void
    {
        $products = Product::all();
        $warehouses = Warehouse::all();
        $users = User::all();

        if ($products->isEmpty() || $warehouses->count() < 2 || $users->isEmpty()) {
            return;
        }

        for ($i = 1; $i <= 5; $i++) {
            $fromWarehouse = $warehouses->random();
            $toWarehouse = $warehouses->where('id', '!=', $fromWarehouse->id)->random();
            $status = fake()->randomElement(['draft', 'pending', 'in_transit', 'completed']);

            $transfer = Transfer::create([
                'from_warehouse_id' => $fromWarehouse->id,
                'to_warehouse_id' => $toWarehouse->id,
                'status' => $status,
                'created_by' => $users->random()->id,
                'completed_by' => in_array($status, ['completed']) ? $users->random()->id : null,
                'note' => 'Transfer barang dari ' . $fromWarehouse->name . ' ke ' . $toWarehouse->name,
            ]);

            $numItems = fake()->numberBetween(1, 5);
            $selectedProducts = $products->random($numItems);

            foreach ($selectedProducts as $product) {
                TransferItem::create([
                    'transfer_id' => $transfer->id,
                    'product_id' => $product->id,
                    'quantity' => fake()->numberBetween(1, 50),
                    'received_quantity' => $status === 'completed' ? fake()->numberBetween(1, 50) : 0,
                ]);
            }
        }
    }
}