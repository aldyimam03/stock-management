<?php

namespace Database\Seeders;

use App\Models\Shipment;
use App\Models\NumberSeries;
use App\Models\SalesOrder;
use App\Models\Warehouse;
use App\Models\User;
use Illuminate\Database\Seeder;

class ShipmentSeeder extends Seeder
{
    public function run(): void
    {
        $salesOrders = SalesOrder::where('status', 'confirmed')->get();
        $warehouses = Warehouse::where('is_active', true)->get();
        $users = User::all();

        if ($salesOrders->isEmpty() || $warehouses->isEmpty()) {
            return;
        }

        foreach ($salesOrders->take(5) as $so) {
            $series = NumberSeries::where('code', 'SH')->first();
            $shipmentNumber = $series->generateNumber();

            $status = random_int(1, 3) === 1 ? 'delivered' : 'shipped';

            Shipment::firstOrCreate(
                ['shipment_number' => $shipmentNumber],
                [
                    'sales_order_id' => $so->id,
                    'warehouse_id' => $warehouses->firstWhere('is_default', true)?->id ?? $warehouses->random()->id,
                    'shipped_date' => now()->subDays(rand(1, 15)),
                    'delivery_date' => $status === 'delivered' ? now()->subDays(rand(0, 3)) : null,
                    'status' => $status,
                    'created_by' => $users->random()->id,
                    'notes' => 'Pengiriman barang ke customer',
                ]
            );
        }
    }
}