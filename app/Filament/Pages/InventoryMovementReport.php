<?php

namespace App\Filament\Pages;

use App\Models\StockMovement;
use App\Models\Product;
use App\Models\Warehouse;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use UnitEnum;
use BackedEnum;

class InventoryMovementReport extends Page implements HasTable
{
    use InteractsWithTable;

    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-arrow-path';
    protected static ?string $navigationLabel = 'Mutasi Stok';
    protected static \UnitEnum|string|null $navigationGroup = 'Laporan';
    protected static ?string $title = 'Laporan Mutasi Stok';

    public static function getNavigationBadge(): ?string
    {
        return (string) \App\Models\StockMovement::count();
    }

    public ?string $type = null;
    public ?int $productId = null;
    public ?int $warehouseId = null;
    public ?string $dateFrom = null;
    public ?string $dateTo = null;

    public function table(Table $table): Table
    {
        return $table
            ->query(
                StockMovement::query()
                    ->when($this->type, fn ($q) => $q->where('type', $this->type))
                    ->when($this->productId, fn ($q) => $q->where('product_id', $this->productId))
                    ->when($this->warehouseId, fn ($q) => $q->where('warehouse_id', $this->warehouseId))
                    ->when($this->dateFrom, fn ($q) => $q->whereDate('created_at', '>=', $this->dateFrom))
                    ->when($this->dateTo, fn ($q) => $q->whereDate('created_at', '<=', $this->dateTo))
                    ->with(['product', 'warehouse'])
            )
            ->columns([
                TextColumn::make('created_at')
                    ->label('Tanggal')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('product.name')
                    ->label('Produk')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('product.sku')
                    ->label('SKU')
                    ->searchable(),
                TextColumn::make('warehouse.name')
                    ->label('Warehouse')
                    ->sortable(),
                TextColumn::make('type')
                    ->label('Tipe')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'in' => 'success',
                        'out' => 'danger',
                        'adjustment' => 'warning',
                        'transfer_in' => 'info',
                        'transfer_out' => 'primary',
                        default => 'gray',
                    }),
                TextColumn::make('quantity')
                    ->label('Qty')
                    ->numeric()
                    ->color(fn ($state, $record) => $record->type === 'out' ? 'danger' : 'success'),
                TextColumn::make('reference_type')
                    ->label('Ref. Type')
                    ->toggleable(),
                TextColumn::make('reference_id')
                    ->label('Ref. ID')
                    ->toggleable(),
                TextColumn::make('notes')
                    ->label('Catatan')
                    ->limit(50)
                    ->toggleable(),
            ])
            ->filters([
                SelectFilter::make('type')
                    ->label('Tipe Mutasi')
                    ->options([
                        'in' => 'Masuk',
                        'out' => 'Keluar',
                        'adjustment' => 'Adjustment',
                        'transfer_in' => 'Transfer In',
                        'transfer_out' => 'Transfer Out',
                    ]),
                SelectFilter::make('product_id')
                    ->label('Produk')
                    ->options(Product::pluck('name', 'id')->toArray())
                    ->searchable()
                    ->preload(),
                SelectFilter::make('warehouse_id')
                    ->label('Warehouse')
                    ->options(Warehouse::pluck('name', 'id')->toArray())
                    ->searchable()
                    ->preload(),
                Filter::make('date_from')
                    ->label('Dari Tanggal')
                    ->form([
                        DatePicker::make('date_from')
                            ->label('Dari Tanggal')
                            ->displayFormat('d/m/Y'),
                    ])
                    ->query(fn ($query, $data) => $query->when($data['date_from'], fn ($q) => $q->whereDate('created_at', '>=', $data['date_from']))),
                Filter::make('date_to')
                    ->label('Sampai Tanggal')
                    ->form([
                        DatePicker::make('date_to')
                            ->label('Sampai Tanggal')
                            ->displayFormat('d/m/Y'),
                    ])
                    ->query(fn ($query, $data) => $query->when($data['date_to'], fn ($q) => $q->whereDate('created_at', '<=', $data['date_to']))),
            ])
            ->defaultSort('created_at', 'desc');
    }
}