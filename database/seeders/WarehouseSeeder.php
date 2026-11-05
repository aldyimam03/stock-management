<?php

namespace Database\Seeders;

use App\Models\Warehouse;
use Illuminate\Database\Seeder;

class WarehouseSeeder extends Seeder
{
    public function run(): void
    {
        Warehouse::factory()->count(5)->create();

        Warehouse::create([
            'name' => 'Gudang Utama Jakarta',
            'code' => 'WH-JKT01',
            'slug' => 'gudang-utama-jakarta',
            'address' => 'Kawasan Industri Pulogadung, Jl. Raya Bekasi KM 18',
            'city' => 'Jakarta Timur',
            'state' => 'DKI Jakarta',
            'postal_code' => '13930',
            'country' => 'ID',
            'phone' => '+62-21-46825555',
            'notes' => 'Gudang utama distribusi untuk wilayah Jabodetabek. Kapasitas 5000 pallet.',
            'is_active' => true,
            'is_default' => true,
        ]);

        Warehouse::create([
            'name' => 'Gudang Surabaya',
            'code' => 'WH-SBY01',
            'slug' => 'gudang-surabaya',
            'address' => 'Kawasan Industri Rungkut, Jl. Rungkut Industri Raya No. 10',
            'city' => 'Surabaya',
            'state' => 'Jawa Timur',
            'postal_code' => '60293',
            'country' => 'ID',
            'phone' => '+62-31-8432111',
            'notes' => 'Gudang cabang untuk distribusi Jawa Timur. Kapasitas 2000 pallet.',
            'is_active' => true,
            'is_default' => false,
        ]);

        Warehouse::create([
            'name' => 'Gudang Bandung',
            'code' => 'WH-BDG01',
            'slug' => 'gudang-bandung',
            'address' => 'Kawasan Industri KIIC, Jl. Permata Raya No. 1',
            'city' => 'Karawang',
            'state' => 'Jawa Barat',
            'postal_code' => '41361',
            'country' => 'ID',
            'phone' => '+62-267-8456123',
            'notes' => 'Gudang cabang untuk wilayah Bandung & sekitarnya. Kapasitas 1500 pallet.',
            'is_active' => true,
            'is_default' => false,
        ]);
    }
}