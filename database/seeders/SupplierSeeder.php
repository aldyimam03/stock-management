<?php

namespace Database\Seeders;

use App\Models\Supplier;
use Illuminate\Database\Seeder;

class SupplierSeeder extends Seeder
{
    public function run(): void
    {
        Supplier::factory()->count(8)->create();

        Supplier::create([
            'name' => 'PT Maju Jaya Electronics',
            'code' => 'SUP-MJE',
            'slug' => 'pt-maju-jaya-electronics',
            'email' => 'procurement@majujaya-electronics.co.id',
            'phone' => '+62-21-5550101',
            'address' => 'Jl. Raya Bekasi KM 21 No. 45',
            'city' => 'Bekasi',
            'state' => 'Jawa Barat',
            'postal_code' => '17125',
            'country' => 'ID',
            'notes' => 'Distributor resmi komponen elektronik dan chip IC.',
            'is_active' => true,
        ]);

        Supplier::create([
            'name' => 'CV Sumber Rejeki Packaging',
            'code' => 'SUP-SRP',
            'slug' => 'cv-sumber-rejeki-packaging',
            'email' => 'sales@sumberrejeki-pack.com',
            'phone' => '+62-22-8765432',
            'address' => 'Jl. Cihampelas No. 123',
            'city' => 'Bandung',
            'state' => 'Jawa Barat',
            'postal_code' => '40131',
            'country' => 'ID',
            'notes' => 'Pemasok kemasan kardus, bubble wrap, dan styrofoam.',
            'is_active' => true,
        ]);

        Supplier::create([
            'name' => 'PT Indah Logistik Nusantara',
            'code' => 'SUP-ILN',
            'slug' => 'pt-indah-logistik-nusantara',
            'email' => 'partnership@indahlogistik.co.id',
            'phone' => '+62-31-9988776',
            'address' => 'Jl. Nginden Intan Barat No. 88',
            'city' => 'Surabaya',
            'state' => 'Jawa Timur',
            'postal_code' => '60183',
            'country' => 'ID',
            'notes' => 'Jasa pengiriman dan freight forwarding domestik.',
            'is_active' => true,
        ]);
    }
}