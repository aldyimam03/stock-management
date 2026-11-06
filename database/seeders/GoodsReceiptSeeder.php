<?php

namespace Database\Seeders;

use App\Models\GoodsReceipt;
use App\Models\NumberSeries;
use App\Models\PurchaseOrder;
use App\Models\Warehouse;
use App\Models\User;
use Illuminate\Database\Seeder;

class GoodsReceiptSeeder extends Seeder
{
    public function run(): void
    {
        $purchaseOrders = PurchaseOrder::where('status', '!=', 'cancelled')->get();
        $warehouses = Warehouse::where('is_active', true)->get();
        $users = User::all();

        if ($purchaseOrders->isEmpty() || $warehouses->isEmpty()) {
            return;
        }

        foreach ($purchaseOrders->take(5) as $po) {
            $series = NumberSeries::where('code', 'GR')->first();
            $receiptNumber = $series->generateNumber();

            GoodsReceipt::firstOrCreate(
                ['receipt_number' => $receiptNumber],
                [
                    'purchase_order_id' => $po->id,
                    'warehouse_id' => $warehouses->firstWhere('is_default', true)?->id ?? $warehouses->random()->id,
                    'received_date' => now()->subDays(rand(1, 30)),
                    'status' => 'completed',
                    'created_by' => $users->random()->id,
                    'notes' => 'Penerimaan barang dari purchase order',
                ]
            );
        }
    }
}