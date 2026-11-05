<?php

namespace App\Filament\Resources\Products\Exporters;

use App\Models\Product;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class ProductExporter extends Exporter
{
    protected static ?string $model = Product::class;

    public static function getColumns(): array
    {
        return [
            'name' => 'Nama Produk',
            'slug' => 'Slug',
            'description' => 'Deskripsi',
            'category.name' => 'Kategori',
            'unit' => 'Satuan',
            'cost_price' => 'Harga Beli',
            'selling_price' => 'Harga Jual',
            'stock' => 'Stok',
            'minimum_stock' => 'Stok Minimum',
            'is_active' => 'Aktif',
        ];
    }

    public static function getColumnFormatters(): array
    {
        return [
            'category.name' => fn (Product $product) => $product->category?->name ?? '-',
            'is_active' => fn (Product $product) => $product->is_active ? 'Ya' : 'Tidak',
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Ekspor produk Anda selesai. ' . number_format($export->successful_rows) . ' baris diekspor.';

        if ($export->failed_rows > 0) {
            $body .= ' ' . number_format($export->failed_rows) . ' baris gagal diekspor.';
        }

        return $body;
    }
}