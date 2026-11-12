<?php

namespace App\Console\Commands;

use App\Models\Notification;
use App\Models\Product;
use App\Models\StockBalance;
use App\Models\User;
use Illuminate\Console\Command;

class CheckLowStockCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stock:check-low';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Pemeriksaan rutin stok produk terhadap batas minimum dan kirim notifikasi';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $products = Product::where('is_active', true)->get();
        $admins = User::where('role', 'admin')->get();
        $alertCount = 0;

        foreach ($products as $product) {
            $totalStock = (float) StockBalance::where('product_id', $product->id)->sum('quantity');

            if ($totalStock <= (float) $product->minimum_stock) {
                $alertCount++;
                $this->warn("Low Stock: {$product->name} (Sisa: {$totalStock}, Min: {$product->minimum_stock})");

                foreach ($admins as $admin) {
                    Notification::create([
                        'user_id' => $admin->id,
                        'type' => 'low_stock',
                        'title' => "Peringatan Stok Rendah: {$product->name}",
                        'message' => "Stok produk {$product->name} tersisa {$totalStock} unit (batas minimum: {$product->minimum_stock}).",
                        'data' => [
                            'product_id' => $product->id,
                            'slug' => $product->slug,
                            'current_stock' => $totalStock,
                            'minimum_stock' => $product->minimum_stock,
                        ],
                    ]);
                }
            }
        }

        $this->info("Pemeriksaan selesai. Ditemukan {$alertCount} produk dengan stok di bawah minimum.");

        return Command::SUCCESS;
    }
}
