<?php

namespace App\Filament\Staff\Resources\GoodsReceipts\Tables;

use App\Models\Product;
use Filament\Actions\EditAction;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Tables\Columns\GroupedTextColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class GoodsReceiptsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('receipt_number')
                    ->label('No Penerimaan')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                GroupedTextColumn::make([
                    TextColumn::make('product_code')
                        ->label('Code')
                        ->searchable(),
                    TextColumn::make('product_name')
                        ->label('Nama')
                        ->toggleable(),
                    TextColumn::make('quantity')
                        ->label('Qty')
                        ->sortable(),
                    TextColumn::make('unit_price')
                        ->label('Harga')
                        ->money('IDR')
                        ->toggleable(),
                ]),
                TextColumn::make('warehouse.name')
                    ->label('Warehouse')
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('received_date')
                    ->label('Tanggal Terima')
                    ->date()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'draft' => 'gray',
                        'pending' => 'warning',
                        'confirmed' => 'info',
                        'received' => 'success',
                        'cancelled' => 'danger',
                        default => 'gray',
                    })
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('purchase_order_id')
                    ->label('PO')
                    ->relationship('purchaseOrder', 'order_number'),
                SelectFilter::make('warehouse_id')
                    ->label('Warehouse')
                    ->relationship('warehouse', 'name'),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }
}