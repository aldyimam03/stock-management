<?php

namespace App\Filament\Resources\Customers\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class CustomersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('code')
                    ->label('Kode')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('name')
                    ->label('Nama')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('phone')
                    ->label('Telepon')
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('city')
                    ->label('Kota')
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('credit_limit')
                    ->label('Batas Kredit')
                    ->money('IDR')
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('balance')
                    ->label('Piutang')
                    ->money('IDR')
                    ->sortable()
                    ->toggleable()
                    ->color(fn ($state) => $state > 0 ? 'danger' : 'success'),
                TextColumn::make('is_active')
                    ->label('Aktif')
                    ->badge()
                    ->color(fn (bool $state): string => $state ? 'success' : 'danger')
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TrashedFilter::make(),
                SelectFilter::make('is_active')
                    ->label('Status')
                    ->options([
                        '1' => 'Aktif',
                        '0' => 'Tidak Aktif',
                    ]),
                SelectFilter::make('country')
                    ->label('Negara')
                    ->options([
                        'ID' => 'Indonesia',
                    ]),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }
}
