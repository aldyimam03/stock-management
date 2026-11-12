<?php

namespace App\Filament\Resources\Shipments\Schemas;

use App\Models\Product;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;

class ShipmentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make(2)->schema([
                    Select::make('sales_order_id')
                        ->label('Sales Order')
                        ->relationship('salesOrder', 'order_number')
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
                    DatePicker::make('shipped_date')
                        ->label('Tanggal Kirim')
                        ->default(now())
                        ->required(),
                    DatePicker::make('delivery_date')
                        ->label('Tanggal Diterima')
                        ->nullable(),
                    Select::make('status')
                        ->label('Status')
                        ->options([
                            'pending' => 'Pending',
                            'shipped' => 'Shipped',
                            'delivered' => 'Delivered',
                            'cancelled' => 'Cancelled',
                        ])
                        ->required()
                        ->default('pending'),
                ]),
                Repeater::make('items')
                    ->label('Barang yang Dikirim')
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
                                ->label('Kuantitas Dikirim')
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
                    ->defaultItems(0)
                    ->reorderableWithButtons()
                    ->addActionLabel('Tambah Barang Dikirim')
                    ->helperText('Jika dibiarkan kosong, sistem akan otomatis memproses seluruh item dari Sales Order.'),
                Textarea::make('notes')
                    ->label('Catatan')
                    ->rows(2)
                    ->columnSpanFull(),
            ]);
    }
}
