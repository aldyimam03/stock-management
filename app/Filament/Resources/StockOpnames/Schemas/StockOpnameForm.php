<?php

namespace App\Filament\Resources\StockOpnames\Schemas;

use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Select;
use Filament\Schemas\Components\DatePicker;
use Filament\Schemas\Components\Textarea;
use Filament\Schemas\Schema;

class StockOpnameForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make(2)->schema([
                    Select::make('warehouse_id')
                        ->label('Warehouse')
                        ->relationship('warehouse', 'name')
                        ->required()
                        ->searchable()
                        ->preload(),

                    DatePicker::make('opname_date')
                        ->label('Opname Date')
                        ->required()
                        ->default(now()),

                    Select::make('status')
                        ->label('Status')
                        ->options([
                            'draft' => 'Draft',
                            'in_progress' => 'In Progress',
                            'completed' => 'Completed',
                        ])
                        ->default('draft')
                        ->required(),

                    Select::make('created_by')
                        ->label('Created By')
                        ->relationship('createdByUser', 'name')
                        ->required()
                        ->searchable()
                        ->preload()
                        ->default(auth()->id()),

                    Textarea::make('notes')
                        ->label('Notes')
                        ->columnSpanFull(),
                ]),
            ]);
    }
}
