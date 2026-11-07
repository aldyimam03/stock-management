<?php

namespace App\Filament\Staff\Resources\Transfers\Tables;

use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class TransfersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('transfer_number')
                    ->label('No Transfer')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                TextColumn::make('fromWarehouse.name')
                    ->label('Dari Warehouse')
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('toWarehouse.name')
                    ->label('Ke Warehouse')
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('transfer_date')
                    ->label('Tanggal Transfer')
                    ->date()
                    ->sortable(),
                TextColumn::make('expected_date')
                    ->label('Estimasi Tiba')
                    ->date()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'draft' => 'gray',
                        'pending' => 'warning',
                        'in_transit' => 'info',
                        'arrived' => 'success',
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
                SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'draft' => 'Draft',
                        'pending' => 'Pending',
                        'in_transit' => 'In Transit',
                        'arrived' => 'Arrived',
                        'cancelled' => 'Cancelled',
                    ]),
                SelectFilter::make('from_warehouse_id')
                    ->label('Dari Warehouse')
                    ->relationship('fromWarehouse', 'name'),
                SelectFilter::make('to_warehouse_id')
                    ->label('Ke Warehouse')
                    ->relationship('toWarehouse', 'name'),
            ])
            ->actions([
                EditAction::make(),
            ]);
    }
}