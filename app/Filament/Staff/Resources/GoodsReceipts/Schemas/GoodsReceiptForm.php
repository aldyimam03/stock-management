<?php

namespace App\Filament\Staff\Resources\GoodsReceipts\Schemas;

use App\Models\Product;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;
use Filament\Schemas\Schemas;

class GoodsReceiptForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make(2)->schema([
                    Select::make('purchase_order_id')
                        ->label('PO')
                        ->relationship('purchaseOrder', 'order_number')
                        ->required()
                        ->searchable()
                        ->preload(),
                    TextInput::make('receipt_number')
                        ->label('Nomor Penerimaan')
                        ->placeholder(fn (): string => date('Y-GR') . '-GR-' . strtoupper((string) fake()->unique()->numerify('######'))),
                ]),
                Grid::make(2)->schema([
                    TextInput::make('received_date')
                        ->label('Tanggal Terima')
                        ->required()
                        ->default(now()),
                    Select::make('warehouse_id')
                        ->label('Warehouse')
                        ->relationship('warehouse', 'name')
                        ->required()
                        ->searchable()
                        ->preload(),
                ]),
                Grid::make(4)->schema([
                    TextInput::make('product_code')
                        ->label('Code Barang')
                        ->disabled()
                        ->dehydrated(false),
                    TextInput::make('product_name')
                        ->label('Nama Barang')
                        ->disabled()
                        ->dehydrated(false),
                    TextInput::make('quantity')
                        ->label('Jumlah')
                        ->numeric()
                        ->required(),
                    TextInput::make('unit_price')
                        ->label('Harga / Unit (Rp)')
                        ->numeric()
                        ->prefix('Rp')
                        ->default(0),
                ]),
                Textarea::make('notes')
                    ->label('Catatan')
                    ->rows(2)
                    ->columnSpanFull(),
            ]);
    }
}