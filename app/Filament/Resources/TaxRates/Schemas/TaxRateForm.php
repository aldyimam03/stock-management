<?php

namespace App\Filament\Resources\TaxRates\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class TaxRateForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('code')
                    ->required(),
                TextInput::make('name')
                    ->required(),
                TextInput::make('rate')
                    ->required()
                    ->numeric(),
                Select::make('type')
                    ->options(['percent' => 'Percent', 'amount' => 'Amount'])
                    ->default('percent')
                    ->required(),
                Toggle::make('is_active')
                    ->required(),
            ]);
    }
}
