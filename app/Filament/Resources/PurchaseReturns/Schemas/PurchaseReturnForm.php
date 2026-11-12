<?php

namespace App\Filament\Resources\PurchaseReturns\Schemas;

use App\Models\Product;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;

class PurchaseReturnForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make(3)->schema([
                    Select::make('goods_receipt_id')
                        ->label('Goods Receipt')
                        ->relationship('goodsReceipt', 'receipt_number')
                        ->required()
                        ->searchable()
                        ->preload(),

                    Select::make('warehouse_id')
                        ->label('Warehouse')
                        ->relationship('warehouse', 'name')
                        ->required()
                        ->searchable()
                        ->preload(),

                    DatePicker::make('return_date')
                        ->label('Tanggal Retur')
                        ->required()
                        ->default(now()),
                ]),
                Grid::make(1)->schema([
                    Select::make('status')
                        ->label('Status')
                        ->options([
                            'pending' => 'Pending',
                            'completed' => 'Completed',
                            'cancelled' => 'Cancelled',
                        ])
                        ->default('pending')
                        ->required(),
                ]),
                Repeater::make('items')
                    ->label('Barang yang Diretur ke Supplier')
                    ->relationship('items')
                    ->schema([
                        Grid::make(3)->schema([
                            Select::make('product_id')
                                ->label('Produk')
                                ->relationship('product', 'name')
                                ->required()
                                ->searchable()
                                ->preload()
                                ->live()
                                ->afterStateUpdated(function ($state, callable $set) {
                                    if ($state) {
                                        $product = Product::find($state);
                                        if ($product) {
                                            $set('unit_price', $product->cost_price);
                                        }
                                    }
                                }),
                            TextInput::make('quantity')
                                ->label('Kuantitas Retur')
                                ->numeric()
                                ->required()
                                ->default(1)
                                ->minValue(0.01)
                                ->step(0.01),
                            TextInput::make('unit_price')
                                ->label('Harga Satuan (Rp)')
                                ->numeric()
                                ->prefix('Rp')
                                ->required()
                                ->default(0),
                        ]),
                    ])
                    ->columns(1)
                    ->defaultItems(1)
                    ->reorderableWithButtons()
                    ->addActionLabel('Tambah Barang Retur'),
                Textarea::make('notes')
                    ->label('Alasan Retur / Catatan')
                    ->rows(2)
                    ->columnSpanFull(),
            ]);
    }
}
