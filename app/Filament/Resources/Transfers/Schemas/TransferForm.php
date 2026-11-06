<?php

namespace App\Filament\Resources\Transfers\Schemas;

use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
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
                Grid::make(2)->schema([
                    Select::make('status')
                        ->label('Status')
                        ->options([
                            'draft' => 'Draft',
                            'pending' => 'Pending',
                            'in_transit' => 'Dalam Perjalanan',
                            'completed' => 'Selesai',
                        ])
                        ->default('draft')
                        ->required(),

                    TextInput::make('note')
                        ->label('Catatan')
                        ->columnSpanFull(),
                ]),
                Repeater::make('items')
                    ->label('Barang Transfer')
                    ->relationship('items')
                    ->schema([
                        Grid::make(2)->schema([
                            Select::make('product_id')
                                ->label('Produk')
                                ->relationship('product', 'name')
                                ->required()
                                ->searchable()
                                ->preload(),
                            TextInput::make('quantity')
                                ->label('Jumlah')
                                ->numeric()
                                ->required()
                                ->step(0.01)
                                ->default(1),
                        ]),
                    ])
                    ->columns(2)
                    ->defaultItems(1)
                    ->reorderableWithButtons()
                    ->addActionLabel('Tambah Barang'),
                Textarea::make('note')
                    ->label('Catatan Transfer')
                    ->rows(2)
                    ->columnSpanFull(),
            ]);
    }
}