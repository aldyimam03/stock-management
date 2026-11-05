<?php

namespace App\Filament\Resources\Customers\Schemas;

use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\Grid;
use Filament\Schemas\Schema;

class CustomerInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Customer')
                    ->schema([
                        Grid::make(2)->schema([
                            TextEntry::make('code')
                                ->label('Kode Customer')
                                ->badge(),
                            TextEntry::make('name')
                                ->label('Nama')
                                ->weight('bold'),
                        ]),
                        Grid::make(2)->schema([
                            TextEntry::make('email')
                                ->label('Email')
                                ->icon('heroicon-m-envelope'),
                            TextEntry::make('phone')
                                ->label('Telepon')
                                ->icon('heroicon-m-phone'),
                        ]),
                        Grid::make(2)->schema([
                            TextEntry::make('credit_limit')
                                ->label('Batas Kredit')
                                ->money('IDR'),
                            TextEntry::make('balance')
                                ->label('Piutang')
                                ->money('IDR')
                                ->color(fn ($state) => $state > 0 ? 'danger' : 'success'),
                        ]),
                        Grid::make(3)->schema([
                            TextEntry::make('city')
                                ->label('Kota'),
                            TextEntry::make('state')
                                ->label('Provinsi'),
                            TextEntry::make('postal_code')
                                ->label('Kode Pos'),
                        ]),
                        TextEntry::make('country')
                            ->label('Negara')
                            ->badge(),
                        TextEntry::make('address')
                            ->label('Alamat')
                            ->columnSpanFull(),
                        TextEntry::make('notes')
                            ->label('Catatan')
                            ->columnSpanFull(),
                        IconEntry::make('is_active')
                            ->label('Status')
                            ->boolean()
                            ->trueIcon('heroicon-o-check-circle')
                            ->falseIcon('heroicon-o-x-circle')
                            ->trueColor('success')
                            ->falseColor('danger'),
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