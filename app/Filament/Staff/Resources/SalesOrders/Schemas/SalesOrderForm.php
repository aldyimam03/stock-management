<?php

namespace App\Filament\Staff\Resources\SalesOrders\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
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
                Textarea::make('notes')
                    ->label('Catatan')
                    ->rows(2)
                    ->columnSpanFull(),
            ]);
    }
}