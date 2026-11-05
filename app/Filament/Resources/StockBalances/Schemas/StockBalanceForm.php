<?php

namespace App\Filament\Resources\StockBalances\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class StockBalanceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('product_id')
                    ->relationship('product', 'name')
                    ->required(),
                Select::make('warehouse_id')
                    ->relationship('warehouse', 'name')
                    ->required(),
                TextInput::make('quantity')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                TextInput::make('reserved_quantity')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                TextInput::make('average_cost')
                    ->required()
                    ->numeric()
                    ->default(0.0)
                    ->prefix('$'),
            ]);
    }
}
