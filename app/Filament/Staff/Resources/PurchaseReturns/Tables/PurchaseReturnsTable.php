<?php

namespace App\Filament\Staff\Resources\PurchaseReturns\Tables;

use Filament\Actions\EditAction;
use Filament\Tables\Columns\GroupedTextColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class PurchaseReturnsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('return_number')
                    ->label('No Retur')
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
                        ->label('Qty Retur')
                        ->numeric()
                        ->sortable(),
                    TextColumn::make('unit_price')
                        ->label('Harga')
                        ->money('IDR')
                        ->toggleable(),
                ]),
                TextColumn::make('goodsReceipt.receipt_number')
                    ->label('GR Asal')
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'draft' => 'gray',
                        'pending' => 'warning',
                        'approved' => 'success',
                        'rejected' => 'danger',
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
                SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'draft' => 'Draft',
                        'pending' => 'Pending',
                        'approved' => 'Approved',
                        'rejected' => 'Rejected',
                    ]),
                SelectFilter::make('goods_receipt_id')
                    ->label('GR Asal')
                    ->relationship('goodsReceipt', 'receipt_number'),
            ])
            ->actions([
                EditAction::make(),
            ]);
    }
}