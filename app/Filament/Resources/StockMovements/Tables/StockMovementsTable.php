<?php

namespace App\Filament\Resources\StockMovements\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class StockMovementsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('created_at')
                    ->label('Tanggal')
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
                TextColumn::make('type')
                    ->label('Tipe')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'in' => 'success',
                        'out' => 'danger',
                        'adjustment' => 'warning',
                        'transfer' => 'info',
                        'return' => 'primary',
                        default => 'gray',
                    }),
                TextColumn::make('quantity')
                    ->label('Jumlah')
                    ->numeric(decimalPlaces: 2)
                    ->sortable(),
                TextColumn::make('unit_price')
                    ->label('Harga Satuan')
                    ->money('IDR')
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('total_value')
                    ->label('Total Nilai')
                    ->money('IDR')
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('supplier.name')
                    ->label('Supplier')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('customer.name')
                    ->label('Customer')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('note')
                    ->label('Catatan')
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('creator.name')
                    ->label('Oleh')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('type')
                    ->label('Tipe Mutasi')
                    ->options([
                        'in' => 'Barang Masuk (In)',
                        'out' => 'Barang Keluar (Out)',
                        'adjustment' => 'Penyesuaian (Adjustment)',
                        'transfer' => 'Transfer',
                        'return' => 'Retur',
                    ]),
                SelectFilter::make('warehouse_id')
                    ->label('Warehouse')
                    ->relationship('warehouse', 'name'),
                SelectFilter::make('product_id')
                    ->label('Produk')
                    ->relationship('product', 'name'),
            ])
            ->recordActions([
                ViewAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
