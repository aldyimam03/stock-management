<?php

namespace App\Filament\Resources\StockOpnames\Schemas;

use App\Models\StockBalance;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;

class StockOpnameForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make(3)->schema([
                    Select::make('warehouse_id')
                        ->label('Warehouse')
                        ->relationship('warehouse', 'name')
                        ->required()
                        ->searchable()
                        ->preload(),

                    DatePicker::make('opname_date')
                        ->label('Tanggal Opname')
                        ->required()
                        ->default(now()),

                    Select::make('status')
                        ->label('Status')
                        ->options([
                            'draft' => 'Draft',
                            'in_progress' => 'In Progress',
                            'completed' => 'Completed',
                        ])
                        ->default('draft')
                        ->required(),
                ]),
                Repeater::make('items')
                    ->label('Barang Opname')
                    ->relationship('items')
                    ->schema([
                        Grid::make(4)->schema([
                            Select::make('product_id')
                                ->label('Produk')
                                ->relationship('product', 'name')
                                ->required()
                                ->searchable()
                                ->preload()
                                ->live()
                                ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                    if ($state) {
                                        $warehouseId = $get('../../warehouse_id');
                                        $balance = StockBalance::where('product_id', $state)
                                            ->where('warehouse_id', $warehouseId)
                                            ->first();
                                        $systemQty = $balance ? (float) $balance->quantity : 0;
                                        $set('system_quantity', $systemQty);
                                        $physicalQty = (float) ($get('physical_quantity') ?? $systemQty);
                                        $set('physical_quantity', $physicalQty);
                                        $set('difference', $physicalQty - $systemQty);
                                    }
                                }),
                            TextInput::make('system_quantity')
                                ->label('Stok Sistem')
                                ->numeric()
                                ->disabled()
                                ->dehydrated()
                                ->default(0),
                            TextInput::make('physical_quantity')
                                ->label('Stok Fisik (Nyata)')
                                ->numeric()
                                ->required()
                                ->default(0)
                                ->live()
                                ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                    $systemQty = (float) ($get('system_quantity') ?? 0);
                                    $physicalQty = (float) ($state ?? 0);
                                    $set('difference', $physicalQty - $systemQty);
                                }),
                            TextInput::make('difference')
                                ->label('Selisih')
                                ->numeric()
                                ->disabled()
                                ->dehydrated()
                                ->default(0),
                        ]),
                    ])
                    ->columns(1)
                    ->defaultItems(0)
                    ->reorderableWithButtons()
                    ->addActionLabel('Tambah Barang Opname'),
                Textarea::make('notes')
                    ->label('Catatan Opname')
                    ->rows(2)
                    ->columnSpanFull(),
            ]);
    }
}
