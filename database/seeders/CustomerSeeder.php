<?php

namespace Database\Seeders;

use App\Models\Customer;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    public function run(): void
    {
        Customer::factory()->count(10)->create();

        Customer::create([
            'name' => 'PT Retail Modern Indonesia',
            'code' => 'CST-RMI',
            'slug' => 'pt-retail-modern-indonesia',
            'email' => 'purchasing@retailmodern.co.id',
            'phone' => '+62-21-7778899',
            'address' => 'Jl. Sudirman Kav. 55, Menara BCA Lt. 15',
            'city' => 'Jakarta Selatan',
            'state' => 'DKI Jakarta',
            'postal_code' => '12190',
            'country' => 'ID',
            'credit_limit' => 500000000,
            'balance' => 125000000,
            'notes' => 'Ritel modern dengan 50+ outlet di Jabodetabek.',
            'is_active' => true,
        ]);

        Customer::create([
            'name' => 'CV Toko Elektronik Jaya',
            'code' => 'CST-TEJ',
            'slug' => 'cv-toko-elektronik-jaya',
            'email' => 'owner@tokoelektronikjaya.com',
            'phone' => '+62-21-6677889',
            'address' => 'Jl. Mangga Dua Raya Blok A No. 12',
            'city' => 'Jakarta Pusat',
            'state' => 'DKI Jakarta',
            'postal_code' => '10730',
            'country' => 'ID',
            'credit_limit' => 200000000,
            'balance' => 45000000,
            'notes' => 'Toko elektronik grosir di Mangga Dua.',
            'is_active' => true,
        ]);

        Customer::create([
            'name' => 'PT Distribusi Nusantara Sejahtera',
            'code' => 'CST-DNS',
            'slug' => 'pt-distribusi-nusantara-sejahtera',
            'email' => 'procurement@dns.co.id',
            'phone' => '+62-274-556677',
            'address' => 'Jl. Ringroad Utara No. 200',
            'city' => 'Yogyakarta',
            'state' => 'DI Yogyakarta',
            'postal_code' => '55281',
            'country' => 'ID',
            'credit_limit' => 1000000000,
            'balance' => 320000000,
            'notes' => 'Distributor regional untuk Jawa Tengah & DIY.',
            'is_active' => true,
        ]);
    }
}
