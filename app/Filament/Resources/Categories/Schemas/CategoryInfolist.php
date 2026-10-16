<?php

namespace App\Filament\Resources\Categories\Schemas;

use App\Models\Category;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class CategoryInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Kategori')
                    ->columns(2)
                    ->components([
                        TextEntry::make('name')
                            ->label('Nama')
                            ->weight('bold'),
                        TextEntry::make('slug')
                            ->label('Slug')
                            ->copyable(),
                        TextEntry::make('description')
                            ->label('Deskripsi')
                            ->placeholder('-')
                            ->columnSpanFull(),
                    ]),
                Section::make('Waktu')
                    ->columns(2)
                    ->components([
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
                            ->visible(fn (Category $record): bool => $record->trashed()),
                    ]),
            ]);
    }
}
