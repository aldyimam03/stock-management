<?php

namespace App\Filament\Pages;

use App\Models\SalesOrder;
use App\Models\Customer;
use App\Models\Warehouse;
use Filament\Forms\Components\DatePicker;
use Filament\Pages\Page;
use Filament\Schemas\Components\EmbeddedTable;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use UnitEnum;
use BackedEnum;

class SalesReport extends Page implements HasTable
{
    use InteractsWithTable;

    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-currency-dollar';
    protected static ?string $navigationLabel = 'Laporan Penjualan';
    protected static \UnitEnum|string|null $navigationGroup = 'Laporan';
    protected static ?string $title = 'Laporan Penjualan';

    public static function getNavigationBadge(): ?string
    {
        return (string) \App\Models\SalesOrder::count();
    }

    public ?string $status = null;
    public ?int $customerId = null;
    public ?int $warehouseId = null;
    public ?string $dateFrom = null;
    public ?string $dateTo = null;

    public function mount(): void
    {
        $this->mountInteractsWithTable();
        $this->bootedInteractsWithTable();
    }

    public function content(Schema $schema): Schema
    {
        return $schema
            ->components([
                EmbeddedTable::make(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                SalesOrder::query()
                    ->when($this->status, fn ($q) => $q->where('status', $this->status))
                    ->when($this->customerId, fn ($q) => $q->where('customer_id', $this->customerId))
                    ->when($this->warehouseId, fn ($q) => $q->where('warehouse_id', $this->warehouseId))
                    ->when($this->dateFrom, fn ($q) => $q->whereDate('order_date', '>=', $this->dateFrom))
                    ->when($this->dateTo, fn ($q) => $q->whereDate('order_date', '<=', $this->dateTo))
                    ->with(['customer', 'warehouse'])
            )
            ->columns([
                TextColumn::make('order_number')
                    ->label('Nomor SO')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                TextColumn::make('customer.name')
                    ->label('Customer')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('warehouse.name')
                    ->label('Warehouse')
                    ->toggleable(),
                TextColumn::make('order_date')
                    ->label('Tanggal SO')
                    ->date()
                    ->sortable(),
                TextColumn::make('expected_delivery_date')
                    ->label('Tanggal Kirim')
                    ->date()
                    ->sortable(),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'draft' => 'gray',
                        'pending' => 'warning',
                        'confirmed' => 'info',
                        'shipped' => 'primary',
                        'delivered' => 'success',
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
                        'shipped' => 'Shipped',
                        'delivered' => 'Delivered',
                        'cancelled' => 'Cancelled',
                    ]),
                SelectFilter::make('customer_id')
                    ->label('Customer')
                    ->options(Customer::pluck('name', 'id')->toArray())
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