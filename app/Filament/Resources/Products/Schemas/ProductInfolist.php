<?php

namespace App\Filament\Resources\Products\Schemas;

use App\Models\Product;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ProductInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Produk')
                    ->columns(2)
                    ->components([
                        TextEntry::make('name')
                            ->label('Nama')
                            ->weight('bold'),
                        TextEntry::make('slug')
                            ->label('Slug')
                            ->copyable(),
                        TextEntry::make('category.name')
                            ->label('Kategori')
                            ->placeholder('-'),
                        TextEntry::make('unit')
                            ->label('Satuan')
                            ->placeholder('kg, pcs, ltr, etc.'),
                        TextEntry::make('description')
                            ->label('Deskripsi')
                            ->placeholder('-')
                            ->columnSpanFull(),
                    ]),
                Section::make('Harga & Stok')
                    ->columns(2)
                    ->components([
                        TextEntry::make('cost_price')
                            ->label('Harga Beli')
                            ->money('IDR', locale: 'id'),
                        TextEntry::make('selling_price')
                            ->label('Harga Jual')
                            ->money('IDR', locale: 'id'),
                        TextEntry::make('stock_quantity')
                            ->label('Stok')
                            ->badge()
                            ->color(fn (Product $record, $state): string => match (true) {
                                $state <= $record->minimum_stock => 'danger',
                                $state <= $record->minimum_stock * 2 => 'warning',
                                default => 'success',
                            }),
                        TextEntry::make('minimum_stock')
                            ->label('Stok Minimum')
                            ->numeric(),
                    ]),
                Section::make('Status & Waktu')
                    ->columns(2)
                    ->components([
                        IconEntry::make('is_active')
                            ->label('Status')
                            ->boolean(),
                        TextEntry::make('created_at')
                            ->label('Dibuat')
                            ->dateTime('d/m/Y H:i')
                            ->placeholder('-'),
                        TextEntry::make('updated_at')
                            ->label('Diperbarui')
                            ->dateTime('d/m/Y H:i')
                            ->placeholder('-'),
                        TextEntry::make('deleted_at')
                            ->label('Dihapus')
                            ->dateTime('d/m/Y H:i')
                            ->placeholder('-')
                            ->visible(fn (Product $record): bool => $record->trashed()),
                    ]),
            ]);
    }
}
