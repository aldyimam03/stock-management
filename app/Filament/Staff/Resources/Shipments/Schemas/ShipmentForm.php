<?php

namespace App\Filament\Staff\Resources\Shipments\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
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
                        ->label('SO')
                        ->relationship('salesOrder', 'order_number')
                        ->required()
                        ->searchable()
                        ->preload(),
                    TextInput::make('shipment_number')
                        ->label('Nomor Pengiriman')
                        ->placeholder(fn (): string => date('Y-SHP') . '-SHIP-' . strtoupper((string) fake()->unique()->numerify('######'))),
                ]),
                Grid::make(3)->schema([
                    TextInput::make('shipped_date')
                        ->label('Tanggal Kirim')
                        ->required()
                        ->default(now()),
                    TextInput::make('delivery_date')
                        ->label('Tanggal Terima')
                        ->placeholder('Akan diisi setelah barang sampai'),
                    Select::make('warehouse_id')
                        ->label('Warehouse')
                        ->relationship('warehouse', 'name')
                        ->required()
                        ->searchable()
                        ->preload(),
                ]),
                Select::make('status')
                    ->label('Status')
                    ->options([
                        'draft' => 'Draft',
                        'pending' => 'Pending',
                        'shipped' => 'Shipped',
                        'delivered' => 'Delivered',
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