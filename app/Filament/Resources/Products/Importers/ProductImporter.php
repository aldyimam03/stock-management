<?php

namespace App\Filament\Resources\Products\Importers;

use App\Models\Category;
use App\Models\Product;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Illuminate\Validation\Rule;

class ProductImporter extends Importer
{
    protected static ?string $model = Product::class;

    public static function getColumns(): array
    {
        return [
            'name',
            'slug',
            'description',
            'category_name' => 'Kategori',
            'unit',
            'cost_price',
            'selling_price',
            'stock',
            'minimum_stock',
            'is_active',
        ];
    }

    public function resolveRecord(): ?Product
    {
        return Product::firstOrNew([
            'slug' => $this->data['slug'],
        ]);
    }

    public static function getCreatedNotificationBody(Import $import): string
    {
        $body = 'Impor produk Anda selesai. ' . number_format($import->successful_rows) . ' baris diimpor.';

        if ($import->failed_rows > 0) {
            $body .= ' ' . number_format($import->failed_rows) . ' baris gagal diimpor.';
        }

        return $body;
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        return self::getCreatedNotificationBody($import);
    }

    public function mapData(array $data): array
    {
        // Cari atau buat kategori berdasarkan nama
        $categoryName = $data['category_name'] ?? $data['category'] ?? null;
        $category = Category::firstOrCreate(
            ['name' => $categoryName],
            ['slug' => \Illuminate\Support\Str::slug($categoryName)]
        );
        $data['category_id'] = $category->id;

        // Generate slug jika kosong
        if (empty($data['slug'])) {
            $data['slug'] = \Illuminate\Support\Str::slug($data['name']);
        }

        // Set is_active
        if (isset($data['is_active'])) {
            $data['is_active'] = in_array(strtolower($data['is_active']), ['ya', '1', 'true', 'aktif']);
        }

        return $data;
    }

    public static function getColumnValidationRules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', Rule::unique(Product::class)],
            'category_name' => ['nullable', 'string'],
            'unit' => ['nullable', 'string', 'max:10'],
            'cost_price' => ['nullable', 'numeric', 'min:0'],
            'selling_price' => ['nullable', 'numeric', 'min:0'],
            'stock' => ['nullable', 'integer', 'min:0'],
            'minimum_stock' => ['nullable', 'integer', 'min:0'],
        ];
    }
}