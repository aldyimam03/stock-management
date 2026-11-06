<?php

namespace Database\Seeders;

use App\Models\PurchaseOrder;
use App\Models\NumberSeries;
use App\Models\Supplier;
use App\Models\Warehouse;
use App\Models\User;
use Illuminate\Database\Seeder;

class PurchaseOrderSeeder extends Seeder
{
    public function run(): void
    {
        $suppliers = Supplier::where('is_active', true)->get();
        $warehouses = Warehouse::where('is_active', true)->get();
        $users = User::all();

        if ($suppliers->isEmpty() || $warehouses->isEmpty()) {
            return;
        }

        $statusSequence = ['draft', 'pending', 'confirmed', 'received', 'cancelled'];

        foreach (range(1, 10) as $i) {
            $series = NumberSeries::where('code', 'PO')->first();
            $orderNumber = $series->generateNumber();

            $status = $statusSequence[array_rand($statusSequence)];

            PurchaseOrder::firstOrCreate(
                ['order_number' => $orderNumber],
                [
                    'supplier_id' => $suppliers->random()->id,
                    'warehouse_id' => $warehouses->random()->id,
                    'order_date' => now()->subDays(rand(1, 60)),
                    'expected_date' => now()->addDays(rand(3, 14)),
                    'status' => $status,
                    'total_amount' => rand(5000000, 50000000),
                    'created_by' => $users->random()->id,
                    'notes' => 'Pesanan pembelian barang untuk stok gudang',
                ]
            );
        }
    }
}