<?php

namespace App\Filament\Resources\Products\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('slug')
                    ->label('Kode')
                    ->unique(ignoreRecord: true)
                    ->copyable()
                    ->default(fn () => Str::slug(fake()->sentence(3)))
                    ->required(),
                TextInput::make('name')
                    ->label('Nama')
                    ->required(),
                Textarea::make('description')
                    ->label('Deskripsi')
                    ->columnSpanFull(),
                Select::make('category_id')
                    ->label('Kategori')
                    ->relationship('category', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
                TextInput::make('unit')
                    ->label('Satuan')
                    ->required()
                    ->placeholder('pcs, kg, ltr, etc.'),
                TextInput::make('cost_price')
                    ->label('Harga Beli')
                    ->required()
                    ->numeric()
                    ->placeholder('3.000')
                    ->prefix('Rp'),
                TextInput::make('selling_price')
                    ->label('Harga Jual')
                    ->required()
                    ->numeric()
                    ->placeholder('3.000')
                    ->prefix('Rp'),
                TextInput::make('stock_quantity')
                    ->label('Stok')
                    ->numeric()
                    ->placeholder('0')
                    ->required(),
                TextInput::make('minimum_stock')
                    ->label('Stok Minimum')
                    ->required()
                    ->numeric()
                    ->placeholder('0'),
                Toggle::make('is_active')
                    ->label('Aktif')
                    ->required(),
            ]);
    }
}
