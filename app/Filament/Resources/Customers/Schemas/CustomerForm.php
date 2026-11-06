<?php

namespace App\Filament\Resources\Customers\Schemas;

use Filament\Schemas\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Grid;
use Filament\Schemas\Schema;

class CustomerForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Customer')
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('name')
                                ->label('Nama Customer')
                                ->required()
                                ->maxLength(255),
                            TextInput::make('code')
                                ->label('Kode Customer')
                                ->maxLength(20)
                                ->unique(ignoreRecord: true),
                        ]),
                        Grid::make(2)->schema([
                            TextInput::make('email')
                                ->label('Email')
                                ->email()
                                ->maxLength(255),
                            TextInput::make('phone')
                                ->label('Telepon')
                                ->tel()
                                ->maxLength(20),
                        ]),
                        Grid::make(2)->schema([
                            TextInput::make('credit_limit')
                                ->label('Batas Kredit (Rp)')
                                ->numeric()
                                ->prefix('Rp')
                                ->default(0),
                            TextInput::make('balance')
                                ->label('Saldo Piutang (Rp)')
                                ->numeric()
                                ->prefix('Rp')
                                ->default(0)
                                ->disabled(),
                        ]),
                        Grid::make(3)->schema([
                            TextInput::make('city')
                                ->label('Kota')
                                ->maxLength(100),
                            TextInput::make('state')
                                ->label('Provinsi')
                                ->maxLength(100),
                            TextInput::make('postal_code')
                                ->label('Kode Pos')
                                ->maxLength(20),
                        ]),
                        TextInput::make('country')
                            ->label('Negara')
                            ->maxLength(2)
                            ->default('ID'),
                        Textarea::make('address')
                            ->label('Alamat')
                            ->rows(2)
                            ->columnSpanFull(),
                        Textarea::make('notes')
                            ->label('Catatan')
                            ->rows(2)
                            ->columnSpanFull(),
                        Toggle::make('is_active')
                            ->label('Aktif')
                            ->default(true),
                    ]),
            ]);
    }
}
