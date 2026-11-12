<?php

namespace App\Filament\Resources\Products\Schemas;

use App\Models\UnitOfMeasure;
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
                    ->label('Kode Produk')
                    ->unique(ignoreRecord: true)
                    ->copyable()
                    ->default(fn () => 'PRD-'.strtoupper(Str::random(6)))
                    ->required(),
                TextInput::make('name')
                    ->label('Nama Produk')
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
                Select::make('unit_of_measure_id')
                    ->label('Satuan (UoM)')
                    ->relationship('unitOfMeasure', 'name')
                    ->searchable()
                    ->preload()
                    ->live()
                    ->afterStateUpdated(function ($state, callable $set) {
                        if ($state) {
                            $uom = UnitOfMeasure::find($state);
                            if ($uom) {
                                $set('unit', $uom->code);
                            }
                        }
                    }),
                TextInput::make('unit')
                    ->label('Simbol Satuan')
                    ->required()
                    ->default('pcs')
                    ->placeholder('pcs, kg, ltr, dll.'),
                TextInput::make('cost_price')
                    ->label('Harga Beli / Modal')
                    ->required()
                    ->numeric()
                    ->prefix('Rp'),
                TextInput::make('selling_price')
                    ->label('Harga Jual')
                    ->required()
                    ->numeric()
                    ->prefix('Rp'),
                TextInput::make('minimum_stock')
                    ->label('Batas Minimum Stok')
                    ->required()
                    ->numeric()
                    ->default(10)
                    ->helperText('Sistem akan memberikan peringatan jika stok berada di bawah batas ini.'),
                Toggle::make('is_active')
                    ->label('Status Aktif')
                    ->default(true)
                    ->required(),
            ]);
    }
}
