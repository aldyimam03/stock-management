<?php

namespace App\Filament\Staff\Resources\PurchaseOrders\Schemas;

use App\Models\Product;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;

class PurchaseOrderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make(2)->schema([
                    Select::make('supplier_id')
                        ->label('Supplier')
                        ->relationship('supplier', 'name')
                        ->required()
                        ->searchable()
                        ->preload(),
                    Select::make('warehouse_id')
                        ->label('Warehouse')
                        ->relationship('warehouse', 'name')
                        ->required()
                        ->searchable()
                        ->preload(),
                ]),
                Grid::make(3)->schema([
                    DatePicker::make('order_date')
                        ->label('Tanggal PO')
                        ->required()
                        ->default(now()),
                    DatePicker::make('expected_date')
                        ->label('Tanggal Diharapkan')
                        ->required(),
                    Select::make('status')
                        ->label('Status')
                        ->options([
                            'draft' => 'Draft',
                            'pending' => 'Pending',
                            'confirmed' => 'Confirmed',
                            'received' => 'Received',
                            'cancelled' => 'Cancelled',
                        ])
                        ->required()
                        ->default('draft')
                        ->suffixIcon('heroicon-m-circle-stack'),
                ]),
                Textarea::make('notes')
                    ->label('Catatan')
                    ->rows(2)
                    ->columnSpanFull(),
            ]);
    }
}