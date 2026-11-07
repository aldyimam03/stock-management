<?php

namespace App\Filament\Staff\Resources\StockOpnames\Tables;

use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class StockOpnamesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('opname_number')
                    ->label('No Opname')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                TextColumn::make('product_code')
                    ->label('Code')
                    ->searchable(),
                TextColumn::make('product_name')
                    ->label('Nama')
                    ->toggleable(),
                TextColumn::make('system_quantity')
                    ->label('Sistem Qty')
                    ->numeric()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('actual_quantity')
                    ->label('Actual Qty')
                    ->numeric()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('warehouse.name')
                    ->label('Warehouse')
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('opname_date')
                    ->label('Tanggal Opname')
                    ->date()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'in_progress' => 'info',
                        'completed' => 'success',
                        'discrepancy' => 'danger',
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
                        'in_progress' => 'Progress',
                        'completed' => 'Completed',
                        'discrepancy' => 'Discrepancy Found',
                    ]),
                SelectFilter::make('warehouse_id')
                    ->label('Warehouse')
                    ->relationship('warehouse', 'name'),
            ])
            ->actions([
                EditAction::make(),
            ]);
    }
}