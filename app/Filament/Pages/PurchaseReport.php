<?php

namespace App\Filament\Pages;

use App\Models\PurchaseOrder;
use App\Models\Supplier;
use App\Models\Warehouse;
use Filament\Forms\Components\DatePicker;
use Filament\Pages\Page;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use UnitEnum;
use BackedEnum;

class PurchaseReport extends Page implements HasTable
{
    use InteractsWithTable;

    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-shopping-bag';
    protected static ?string $navigationLabel = 'Laporan Pembelian';
    protected static \UnitEnum|string|null $navigationGroup = 'Laporan';
    protected static ?string $title = 'Laporan Pembelian';

    public static function getNavigationBadge(): ?string
    {
        return (string) \App\Models\PurchaseOrder::count();
    }

    public ?string $status = null;
    public ?int $supplierId = null;
    public ?int $warehouseId = null;
    public ?string $dateFrom = null;
    public ?string $dateTo = null;

    public function table(Table $table): Table
    {
        return $table
            ->query(
                PurchaseOrder::query()
                    ->when($this->status, fn ($q) => $q->where('status', $this->status))
                    ->when($this->supplierId, fn ($q) => $q->where('supplier_id', $this->supplierId))
                    ->when($this->warehouseId, fn ($q) => $q->where('warehouse_id', $this->warehouseId))
                    ->when($this->dateFrom, fn ($q) => $q->whereDate('order_date', '>=', $this->dateFrom))
                    ->when($this->dateTo, fn ($q) => $q->whereDate('order_date', '<=', $this->dateTo))
                    ->with(['supplier', 'warehouse', 'createdBy'])
            )
            ->columns([
                TextColumn::make('order_number')
                    ->label('Nomor PO')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                TextColumn::make('supplier.name')
                    ->label('Supplier')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('warehouse.name')
                    ->label('Warehouse')
                    ->toggleable(),
                TextColumn::make('order_date')
                    ->label('Tanggal PO')
                    ->date()
                    ->sortable(),
                TextColumn::make('expected_date')
                    ->label('Tanggal Diharapkan')
                    ->date()
                    ->sortable(),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'draft' => 'gray',
                        'pending' => 'warning',
                        'confirmed' => 'info',
                        'received' => 'success',
                        'cancelled' => 'danger',
                        default => 'gray',
                    }),
                TextColumn::make('total_amount')
                    ->label('Total (Rp)')
                    ->money('IDR')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'draft' => 'Draft',
                        'pending' => 'Pending',
                        'confirmed' => 'Confirmed',
                        'received' => 'Received',
                        'cancelled' => 'Cancelled',
                    ]),
                SelectFilter::make('supplier_id')
                    ->label('Supplier')
                    ->options(Supplier::pluck('name', 'id')->toArray())
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
                    ->query(fn ($query, $data) => $query->when($data['date_from'], fn ($q) => $q->whereDate('order_date', '>=', $data['date_from']))),
                Filter::make('date_to')
                    ->label('Sampai Tanggal')
                    ->form([
                        DatePicker::make('date_to')
                            ->label('Sampai Tanggal')
                            ->displayFormat('d/m/Y'),
                    ])
                    ->query(fn ($query, $data) => $query->when($data['date_to'], fn ($q) => $q->whereDate('order_date', '<=', $data['date_to']))),
            ])
            ->defaultSort('order_date', 'desc');
    }
}