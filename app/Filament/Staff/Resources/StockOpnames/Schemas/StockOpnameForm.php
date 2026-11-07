<?php

namespace App\Filament\Staff\Resources\StockOpnames\Schemas;

use App\Models\Product;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;

class StockOpnameForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make(2)->schema([
                    Select::make('warehouse_id')
                        ->label('Warehouse')
                        ->relationship('warehouse', 'name')
                        ->required()
                        ->searchable()
                        ->preload(),
                    TextInput::make('opname_date')
                        ->label('Tanggal Opname')
                        ->required()
                        ->default(now()),
                ]),
                Grid::make(4)->schema([
                    TextInput::make('product_code')
                        ->label('Code Barang')
                        ->disabledState(fn (): null => null),
                    TextInput::make('product_name')
                        ->label('Nama Barang')
                        ->disabledState(fn (): null => null),
                    TextInput::make('system_quantity')
                        ->label('Qty di Sistem')
                        ->numeric()
                        ->disabledState(fn (): null => null)
                        ->suffix('- sistem'),
                    TextInput::make('actual_quantity')
                        ->label('Qty di Gudang (Actual)')
                        ->numeric()
                        ->disabledState(fn (): null => null)
                        ->suffix('- actual'),
                ]),
                Select::make('status')
                    ->label('Status')
                    ->options([
                        'in_progress' => 'Progress',
                        'completed' => 'Completed',
                        'discrepancy' => 'Discrepancy Found',
                    ])
                    ->required()
                    ->default('in_progress')
                    ->suffixIcon('heroicon-m-circle-stack'),
                Textarea::make('notes')
                    ->label('Catatan')
                    ->rows(2)
                    ->columnSpanFull(),
            ]);
    }
}