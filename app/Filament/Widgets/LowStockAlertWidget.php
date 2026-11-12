<?php

namespace App\Filament\Widgets;

use App\Models\Product;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class LowStockAlertWidget extends BaseWidget
{
    protected static ?string $heading = 'Peringatan Stok Rendah';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Product::query()
                    ->leftJoin('stock_balances', 'products.id', '=', 'stock_balances.product_id')
                    ->selectRaw('products.id, products.name, products.slug, products.minimum_stock, COALESCE(SUM(stock_balances.quantity), 0) as total_stock')
                    ->groupBy('products.id', 'products.name', 'products.slug', 'products.minimum_stock')
                    ->havingRaw('COALESCE(SUM(stock_balances.quantity), 0) <= products.minimum_stock')
                    ->orderBy('total_stock', 'asc')
            )
            ->columns([
                TextColumn::make('name')->label('Produk'),
                TextColumn::make('slug')->label('Kode'),
                TextColumn::make('minimum_stock')->label('Min. Stok')->numeric(),
                TextColumn::make('total_stock')
                    ->label('Stok Saat Ini')
                    ->numeric()
                    ->color(fn ($state, $record) => (float) $state <= ((float) $record->minimum_stock / 2) ? 'danger' : 'warning'),
            ])
            ->paginated(false);
    }
}
