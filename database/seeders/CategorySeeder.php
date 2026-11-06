<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Elektronik', 'description' => 'Perangkat elektronik dan aksesoris'],
            ['name' => 'Komputer & Laptop', 'description' => 'Komputer, laptop, dan komponen'],
            ['name' => 'Smartphone & Tablet', 'description' => 'Ponsel pintar dan tablet'],
            ['name' => 'Aksesoris', 'description' => 'Aksesoris pendukung perangkat'],
            ['name' => 'Periferal', 'description' => 'Printer, scanner, monitor, dll'],
            ['name' => 'Jaringan', 'description' => 'Router, switch, kabel jaringan'],
            ['name' => 'Penyimpanan', 'description' => 'HDD, SSD, flash drive, memory card'],
            ['name' => 'Konsol & Game', 'description' => 'Konsol game dan aksesoris gaming'],
            ['name' => 'Audio & Video', 'description' => 'Speaker, headphone, kamera, dll'],
            ['name' => 'Kabel & Konektor', 'description' => 'Kabel data, charger, adapter'],
        ];

        foreach ($categories as $cat) {
            Category::firstOrCreate(
                ['slug' => \Illuminate\Support\Str::slug($cat['name'])],
                $cat
            );
        }
    }
}