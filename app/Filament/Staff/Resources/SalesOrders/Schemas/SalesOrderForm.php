<?php

namespace App\Filament\Staff\Resources\SalesOrders\Schemas;

use App\Models\Product;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;

class SalesOrderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make(2)->schema([
                    Select::make('customer_id')
                        ->label('Customer')
                        ->relationship('customer', 'name')
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
                    DatePicker::make('order_date')
                        ->label('Tanggal SO')
                        ->required()
                        ->default(now()),
                    DatePicker::make('expected_delivery_date')
                        ->label('Tanggal Pengiriman Diharapkan')
                        ->default(now()->addDays(3))
                        ->required(),
                    Select::make('status')
                        ->label('Status')
                        ->options([
                            'draft' => 'Draft',
                            'pending' => 'Pending',
                            'confirmed' => 'Confirmed',
                            'shipped' => 'Shipped',
                            'delivered' => 'Delivered',
                            'cancelled' => 'Cancelled',
                        ])
                        ->required()
                        ->default('draft'),
                ]),
                Repeater::make('items')
                    ->label('Item Pesanan Penjualan')
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
                                            $set('unit_price', $product->selling_price);
                                        }
                                    }
                                }),
                            TextInput::make('quantity')
                                ->label('Kuantitas')
                                ->numeric()
                                ->required()
                                ->default(1)
                                ->minValue(0.01)
                                ->step(0.01),
                            TextInput::make('unit_price')
                                ->label('Harga Jual Satuan (Rp)')
                                ->numeric()
                                ->prefix('Rp')
                                ->required()
                                ->default(0),
                        ]),
                    ])
                    ->columns(1)
                    ->defaultItems(1)
                    ->reorderableWithButtons()
                    ->addActionLabel('Tambah Item Produk'),
                Textarea::make('notes')
                    ->label('Catatan')
                    ->rows(2)
                    ->columnSpanFull(),
            ]);
    }
}
