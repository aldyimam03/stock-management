<?php

namespace App\Filament\Resources\SalesReturns\Schemas;

use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Select;
use Filament\Schemas\Components\DatePicker;
use Filament\Schemas\Components\Textarea;
use Filament\Schemas\Schema;

class SalesReturnForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make(2)->schema([
                    Select::make('shipment_id')
                        ->label('Shipment')
                        ->relationship('shipment', 'shipment_number')
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
                        ->label('Return Date')
                        ->required()
                        ->default(now()),

                    Select::make('status')
                        ->label('Status')
                        ->options([
                            'pending' => 'Pending',
                            'completed' => 'Completed',
                            'cancelled' => 'Cancelled',
                        ])
                        ->default('pending')
                        ->required(),

                    Select::make('created_by')
                        ->label('Created By')
                        ->relationship('createdByUser', 'name')
                        ->required()
                        ->searchable()
                        ->preload()
                        ->default(auth()->id()),

                    Textarea::make('notes')
                        ->label('Notes')
                        ->columnSpanFull(),
                ]),
            ]);
    }
}