<?php

namespace App\Filament\Resources\Suppliers\Schemas;

use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Grid;
use Filament\Schemas\Schema;

class SupplierForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Supplier')
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('name')
                                ->required()
                                ->maxLength(255),
                            TextInput::make('code')
                                ->label('Kode Supplier')
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
