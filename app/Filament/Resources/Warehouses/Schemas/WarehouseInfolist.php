<?php

namespace App\Filament\Resources\Warehouses\Schemas;

use Filament\Schemas\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\Grid;
use Filament\Schemas\Schema;

class WarehouseInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Warehouse')
                    ->schema([
                        Grid::make(2)->schema([
                            TextEntry::make('code')
                                ->label('Kode Warehouse')
                                ->badge(),
                            TextEntry::make('name')
                                ->label('Nama')
                                ->weight('bold'),
                        ]),
                        Grid::make(2)->schema([
                            TextEntry::make('phone')
                                ->label('Telepon')
                                ->icon('heroicon-m-phone'),
                            TextEntry::make('country')
                                ->label('Negara')
                                ->badge(),
                        ]),
                        Grid::make(3)->schema([
                            TextEntry::make('city')
                                ->label('Kota'),
                            TextEntry::make('state')
                                ->label('Provinsi'),
                            TextEntry::make('postal_code')
                                ->label('Kode Pos'),
                        ]),
                        TextEntry::make('address')
                            ->label('Alamat')
                            ->columnSpanFull(),
                        TextEntry::make('notes')
                            ->label('Catatan')
                            ->columnSpanFull(),
                        Grid::make(2)->schema([
                            IconEntry::make('is_active')
                                ->label('Status Aktif')
                                ->boolean()
                                ->trueIcon('heroicon-o-check-circle')
                                ->falseIcon('heroicon-o-x-circle')
                                ->trueColor('success')
                                ->falseColor('danger'),
                            IconEntry::make('is_default')
                                ->label('Default Warehouse')
                                ->boolean()
                                ->trueIcon('heroicon-o-check-circle')
                                ->falseIcon('heroicon-o-minus-circle')
                                ->trueColor('success')
                                ->falseColor('gray'),
                        ]),
                    ]),
                Section::make('Timestamp')
                    ->schema([
                        Grid::make(2)->schema([
                            TextEntry::make('created_at')
                                ->label('Dibuat')
                                ->dateTime()
                                ->icon('heroicon-m-calendar-days'),
                            TextEntry::make('updated_at')
                                ->label('Diperbarui')
                                ->dateTime()
                                ->icon('heroicon-m-arrow-path'),
                        ]),
                        TextEntry::make('deleted_at')
                            ->label('Dihapus')
                            ->dateTime()
                            ->icon('heroicon-m-trash')
                            ->placeholder('Belum dihapus'),
                    ])
                    ->collapsible(),
            ]);
    }
}