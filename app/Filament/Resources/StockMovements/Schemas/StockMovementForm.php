<?php

namespace App\Filament\Resources\StockMovements\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;

class StockMovementForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make(2)->schema([
                    Select::make('product_id')
                        ->label('Produk')
                        ->relationship('product', 'name')
                        ->required()
                        ->searchable()
                        ->preload(),
                    Select::make('warehouse_id')
                        ->label('Warehouse')
                        ->relationship('warehouse', 'name')
                        ->required()
                        ->searchable()
                        ->preload(),
                ]),
                Grid::make(3)->schema([
                    Select::make('type')
                        ->label('Tipe Mutasi')
                        ->options([
                            'in' => 'Barang Masuk (In)',
                            'out' => 'Barang Keluar (Out)',
                            'adjustment' => 'Penyesuaian (Adjustment)',
                            'transfer' => 'Transfer',
                            'return' => 'Retur',
                        ])
                        ->required(),
                    TextInput::make('quantity')
                        ->label('Jumlah')
                        ->numeric()
                        ->required()
                        ->step(0.01),
                    TextInput::make('unit_price')
                        ->label('Harga Satuan (Rp)')
                        ->numeric()
                        ->prefix('Rp')
                        ->default(0),
                ]),
                Grid::make(2)->schema([
                    Select::make('supplier_id')
                        ->label('Supplier (Opsional)')
                        ->relationship('supplier', 'name')
                        ->searchable()
                        ->nullable(),
                    Select::make('customer_id')
                        ->label('Customer (Opsional)')
                        ->relationship('customer', 'name')
                        ->searchable()
                        ->nullable(),
                ]),
                Textarea::make('note')
                    ->label('Catatan / Alasan')
                    ->rows(2)
                    ->columnSpanFull(),
            ]);
    }
}