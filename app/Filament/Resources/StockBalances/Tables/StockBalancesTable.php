<?php

namespace App\Filament\Resources\StockBalances\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class StockBalancesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('created_at')
                    ->label('Terakhir Update')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('product.name')
                    ->label('Produk')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('warehouse.name')
                    ->label('Warehouse')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('quantity')
                    ->label('Stok Fisik')
                    ->numeric(decimalPlaces: 2)
                    ->sortable(),
                TextColumn::make('reserved_quantity')
                    ->label('Stok Teresan')
                    ->numeric(decimalPlaces: 2)
                    ->sortable(),
                TextColumn::make('available_quantity')
                    ->label('Stok Tersedia')
                    ->numeric(decimalPlaces: 2)
                    ->sortable()
                    ->color(fn ($state) => $state > 0 ? 'success' : 'danger'),
                TextColumn::make('average_cost')
                    ->label('Harga Rata-rata')
                    ->money('IDR')
                    ->sortable()
                    ->toggleable(),
            ])
            ->filters([
                //
            ])
            ->recordActions([])
            ->toolbarActions([]);
    }
}
