<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        User::factory()->create([
            'name' => 'Staff User',
            'email' => 'staff@example.com',
            'password' => bcrypt('password'),
            'role' => 'staff',
        ]);

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
            'role' => 'staff',
        ]);

        $this->call([
            SupplierSeeder::class,
            CustomerSeeder::class,
            WarehouseSeeder::class,
            UnitOfMeasureSeeder::class,
            NumberSeriesSeeder::class,
            TaxRateSeeder::class,
            CategorySeeder::class,
            ProductSeeder::class,
            PurchaseOrderSeeder::class,
            SalesOrderSeeder::class,
            GoodsReceiptSeeder::class,
            ShipmentSeeder::class,
            StockMovementSeeder::class,
            StockBalanceSeeder::class,
            NotificationSeeder::class,
            TransferSeeder::class,
        ]);
    }
}
