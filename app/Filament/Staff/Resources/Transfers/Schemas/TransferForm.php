<?php

namespace App\Filament\Staff\Resources\Transfers\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;

class TransferForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make(2)->schema([
                    Select::make('from_warehouse_id')
                        ->label('Dari Warehouse')
                        ->relationship('fromWarehouse', 'name')
                        ->required()
                        ->searchable()
                        ->preload(),
                    Select::make('to_warehouse_id')
                        ->label('Ke Warehouse')
                        ->relationship('toWarehouse', 'name')
                        ->required()
                        ->searchable()
                        ->preload(),
                ]),
                Grid::make(3)->schema([
                    TextInput::make('transfer_number')
                        ->label('No Transfer')
                        ->placeholder(fn (): string => date('Y-TRF') . '-TRN-' . strtoupper((string) fake()->unique()->numerify('######'))),
                    DatePicker::make('transfer_date')
                        ->label('Tanggal Transfer')
                        ->required()
                        ->default(now()),
                    DatePicker::make('expected_date')
                        ->label('Estimasi Tiba'),
                ]),
                Select::make('status')
                    ->label('Status')
                    ->options([
                        'draft' => 'Draft',
                        'pending' => 'Pending',
                        'in_transit' => 'In Transit',
                        'arrived' => 'Arrived',
                        'cancelled' => 'Cancelled',
                    ])
                    ->required()
                    ->default('draft'),
                Textarea::make('notes')
                    ->label('Catatan')
                    ->rows(2)
                    ->columnSpanFull(),
            ]);
    }
}