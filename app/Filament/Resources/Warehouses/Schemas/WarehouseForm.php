<?php

namespace App\Filament\Resources\Warehouses\Schemas;

use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Grid;
use Filament\Schemas\Schema;

class WarehouseForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Warehouse')
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('name')
                                ->label('Nama Warehouse')
                                ->required()
                                ->maxLength(255),
                            TextInput::make('code')
                                ->label('Kode Warehouse')
                                ->maxLength(20)
                                ->unique(ignoreRecord: true),
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
                        TextInput::make('phone')
                            ->label('Telepon')
                            ->tel()
                            ->maxLength(20),
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
                        Toggle::make('is_default')
                            ->label('Default Warehouse')
                            ->default(false),
                    ]),
            ]);
    }
}
