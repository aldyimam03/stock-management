<?php

namespace App\Filament\Widgets;

use App\Models\StockBalance;
use App\Models\Product;
use Filament\Widgets\TableWidget as BaseWidget;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;

class LowStockAlertWidget extends BaseWidget
{
    public function table(Table $table): Table
    {
        return $table
            ->query(
                Product::query()
                    ->join('stock_balances', 'products.id', '=', 'stock_balances.product_id')
                    ->selectRaw('products.*, SUM(stock_balances.quantity) as total_stock')
                    ->groupBy('products.id')
                    ->havingRaw('SUM(stock_balances.quantity) < 10')
                    ->orderBy('total_stock', 'asc')
            )
            ->columns([
                TextColumn::make('name')->label('Produk'),
                TextColumn::make('sku')->label('SKU'),
                TextColumn::make('total_stock')
                    ->label('Stok')
                    ->numeric()
                    ->color(fn ($state) => $state < 5 ? 'danger' : 'warning'),
            ])
            ->paginated(false);
    }
}