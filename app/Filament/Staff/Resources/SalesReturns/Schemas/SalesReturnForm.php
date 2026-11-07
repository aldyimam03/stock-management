<?php

namespace App\Filament\Staff\Resources\SalesReturns\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;

class SalesReturnForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make(2)->schema([
                    Select::make('shipment_id')
                        ->label('Pengiriman (Shipment)')
                        ->relationship('shipment', 'shipment_number')
                        ->required()
                        ->searchable()
                        ->preload(),
                    TextInput::make('return_number')
                        ->label('No Retur')
                        ->placeholder(fn (): string => date('Y-SR') . '-SR-' . strtoupper((string) fake()->unique()->numerify('######'))),
                ]),
                Grid::make(4)->schema([
                    TextInput::make('product_code')
                        ->label('Code Barang')
                        ->disabledState(fn (): null => null),
                    TextInput::make('product_name')
                        ->label('Nama Barang')
                        ->disabledState(fn (): null => null),
                    TextInput::make('quantity')
                        ->label('Qty Retur')
                        ->numeric()
                        ->required(),
                    TextInput::make('unit_price')
                        ->label('Harga / Unit (Rp)')
                        ->numeric()
                        ->prefix('Rp')
                        ->default(0),
                ]),
                Select::make('status')
                    ->label('Status')
                    ->options([
                        'draft' => 'Draft',
                        'pending' => 'Pending',
                        'approved' => 'Approved',
                        'rejected' => 'Rejected',
                    ])
                    ->required()
                    ->default('draft'),
                Textarea::make('reason')
                    ->label('Alasan Retur')
                    ->rows(2)
                    ->columnSpanFull(),
                Textarea::make('notes')
                    ->label('Catatan')
                    ->rows(2)
                    ->columnSpanFull(),
            ]);
    }
}