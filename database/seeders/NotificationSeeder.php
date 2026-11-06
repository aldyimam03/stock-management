<?php

namespace Database\Seeders;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Database\Seeder;

class NotificationSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();

        if ($users->isEmpty()) {
            return;
        }

        $notifications = [
            ['type' => 'info', 'title' => 'Sistem siap digunakan', 'message' => 'Selamat datang di Stock Management System. Semua fitur sudah siap digunakan.', 'is_read' => true],
            ['type' => 'warning', 'title' => 'Stok rendah: Laptop ASUS', 'message' => 'Stok Laptop ASUS VivoBook 14 tersisa 3 unit di bawah batas minimum (5).', 'is_read' => false],
            ['type' => 'success', 'title' => 'PO-000005 diterima', 'message' => 'Goods Receipt GR-000001 untuk Purchase Order PO-000005 telah selesai diproses.', 'is_read' => false],
            ['type' => 'info', 'title' => 'Pengiriman SO-000003', 'message' => 'Shipment SH-000001 untuk Sales Order SO-000003 telah dikirim ke customer.', 'is_read' => true],
            ['type' => 'danger', 'title' => 'Error sinkronisasi stok', 'message' => 'Terjadi kesalahan saat sinkronisasi stok antara gudang Pusat dan Cabang.', 'is_read' => false],
            ['type' => 'warning', 'title' => 'Pembayaran PO-000002 jatuh tempo', 'message' => 'Pembayaran untuk Purchase Order PO-000002 akan jatuh tempo dalam 3 hari.', 'is_read' => false],
            ['type' => 'info', 'title' => 'Kategori baru ditambahkan', 'message' => 'Kategori "Aksesoris Mobile" telah ditambahkan ke sistem.', 'is_read' => true],
            ['type' => 'success', 'title' => 'Laporan bulanan tersedia', 'message' => 'Laporan stok dan transaksi bulan Oktober 2024 sudah dapat diunduh.', 'is_read' => true],
        ];

        foreach ($notifications as $notification) {
            $user = $users->random();
            Notification::firstOrCreate(
                ['user_id' => $user->id, 'title' => $notification['title']],
                array_merge($notification, ['user_id' => $user->id])
            );
        }

        Notification::factory()->count(15)->create([
            'user_id' => $users->random()->id,
            'is_read' => rand(0, 1),
        ]);
    }
}