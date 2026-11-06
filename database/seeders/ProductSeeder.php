<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $categories = Category::all();

        $products = [
            ['name' => 'Laptop ASUS VivoBook 14', 'category_id' => 2, 'cost_price' => 7500000, 'selling_price' => 8500000, 'minimum_stock' => 5, 'unit' => 'PCS'],
            ['name' => 'iPhone 14 Pro Max 256GB', 'category_id' => 3, 'cost_price' => 18000000, 'selling_price' => 21000000, 'minimum_stock' => 3, 'unit' => 'PCS'],
            ['name' => 'Samsung Galaxy S23 Ultra', 'category_id' => 3, 'cost_price' => 15000000, 'selling_price' => 18000000, 'minimum_stock' => 5, 'unit' => 'PCS'],
            ['name' => 'Logitech MX Master 3S', 'category_id' => 4, 'cost_price' => 1200000, 'selling_price' => 1500000, 'minimum_stock' => 10, 'unit' => 'PCS'],
            ['name' => 'TP-Link Archer AX73', 'category_id' => 6, 'cost_price' => 900000, 'selling_price' => 1200000, 'minimum_stock' => 8, 'unit' => 'PCS'],
            ['name' => 'Samsung 980 PRO 1TB NVMe', 'category_id' => 7, 'cost_price' => 1800000, 'selling_price' => 2200000, 'minimum_stock' => 10, 'unit' => 'PCS'],
            ['name' => 'Sony PlayStation 5', 'category_id' => 8, 'cost_price' => 6500000, 'selling_price' => 7500000, 'minimum_stock' => 5, 'unit' => 'PCS'],
            ['name' => 'JBL Flip 6 Speaker', 'category_id' => 9, 'cost_price' => 1200000, 'selling_price' => 1500000, 'minimum_stock' => 10, 'unit' => 'PCS'],
            ['name' => 'Kabel HDMI 2m Premium', 'category_id' => 10, 'cost_price' => 50000, 'selling_price' => 85000, 'minimum_stock' => 50, 'unit' => 'PCS'],
            ['name' => 'Mouse Pad XXL Gaming', 'category_id' => 4, 'cost_price' => 75000, 'selling_price' => 120000, 'minimum_stock' => 30, 'unit' => 'PCS'],
        ];

        foreach ($products as $product) {
            Product::firstOrCreate(
                ['slug' => \Illuminate\Support\Str::slug($product['name'])],
                array_merge($product, [
                    'description' => 'Produk berkualitas tinggi dari ' . ($categories->firstWhere('id', $product['category_id'])?->name ?? ''),
                    'stock_quantity' => rand(20, 200),
                    'is_active' => true,
                ])
            );
        }

        Product::factory()->count(20)->create([
            'is_active' => true,
            'stock_quantity' => rand(10, 150),
        ]);
    }
}