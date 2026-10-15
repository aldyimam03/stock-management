<?php

namespace App\Filament\Resources\Products\Schemas;

use App\Models\Product;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class ProductInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('slug')
                    ->label('Slug')
                    ->copyable(),
                TextEntry::make('name')
                    ->label('Nama'),
                TextEntry::make('description')
                    ->label('Deskripsi')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('category.name')
                    ->label('Kategori')
                    ->placeholder('-'),
                TextEntry::make('unit')
                    ->label('Satuan')
                    ->placeholder('kg, pcs, ltr, etc.'),
                TextEntry::make('cost_price')
                    ->label('Harga Beli')
                    ->money('IDR', locale: 'id'),
                TextEntry::make('selling_price')
                    ->label('Harga Jual')
                    ->money('IDR', locale: 'id'),
                TextEntry::make('stock_quantity')
                    ->label('Stok')
                    ->numeric(),
                TextEntry::make('minimum_stock')
                    ->label('Stok Minimum')
                    ->numeric(),
                IconEntry::make('is_active')
                    ->label('Status')
                    ->boolean(),
                TextEntry::make('created_at')
                    ->label('Dibuat')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->label('Diperbarui')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('deleted_at')
                    ->label('Dihapus')
                    ->dateTime()
                    ->visible(fn (Product $record): bool => $record->trashed()),
            ]);
    }
}
