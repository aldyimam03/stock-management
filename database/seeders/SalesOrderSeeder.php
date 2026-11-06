<?php

namespace Database\Seeders;

use App\Models\SalesOrder;
use App\Models\NumberSeries;
use App\Models\Customer;
use App\Models\Warehouse;
use App\Models\User;
use Illuminate\Database\Seeder;

class SalesOrderSeeder extends Seeder
{
    public function run(): void
    {
        $customers = Customer::where('is_active', true)->get();
        $warehouses = Warehouse::where('is_active', true)->get();
        $users = User::all();

        if ($customers->isEmpty() || $warehouses->isEmpty()) {
            return;
        }

        $statusSequence = ['draft', 'pending', 'confirmed', 'shipped', 'delivered', 'cancelled'];

        foreach (range(1, 10) as $i) {
            $series = NumberSeries::where('code', 'SO')->first();
            $orderNumber = $series->generateNumber();

            $status = $statusSequence[array_rand($statusSequence)];

            SalesOrder::firstOrCreate(
                ['order_number' => $orderNumber],
                [
                    'customer_id' => $customers->random()->id,
                    'warehouse_id' => $warehouses->random()->id,
                    'order_date' => now()->subDays(rand(1, 60)),
                    'expected_delivery_date' => now()->addDays(rand(3, 14)),
                    'status' => $status,
                    'total_amount' => rand(5000000, 50000000),
                    'created_by' => $users->random()->id,
                    'notes' => 'Pesanan penjualan untuk pelanggan',
                ]
            );
        }
    }
}