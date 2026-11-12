<?php

namespace App\Filament\Pages;

use App\Exports\StockReportExport;
use App\Models\StockBalance;
use App\Models\Warehouse;
use Filament\Actions\Action;
use Filament\Pages\Page;
use Filament\Schemas\Components\EmbeddedTable;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Maatwebsite\Excel\Facades\Excel;

class StockReport extends Page implements HasTable
{
    use InteractsWithTable;

    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-cube-transparent';

    protected static ?string $navigationLabel = 'Laporan Stok';

    protected static \UnitEnum|string|null $navigationGroup = 'Laporan';

    protected static ?string $title = 'Laporan Stok Inventory';

    public static function getNavigationBadge(): ?string
    {
        return (string) StockBalance::count();
    }

    public ?int $warehouseId = null;

    public function mount(): void
    {
        $this->mountInteractsWithTable();
        $this->warehouseId = null;
        $this->bootedInteractsWithTable();
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('export_excel')
                ->label('Ekspor ke Excel')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('success')
                ->action(function () {
                    $filename = 'laporan-stok-'.date('Y-m-d-His').'.xlsx';

                    return Excel::download(new StockReportExport($this->warehouseId), $filename);
                }),
        ];
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
                StockBalance::query()
                    ->when($this->warehouseId, fn ($q) => $q->where('warehouse_id', $this->warehouseId))
                    ->with(['product', 'warehouse'])
            )
            ->columns([
                TextColumn::make('product.name')
                    ->label('Produk')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('product.slug')
                    ->label('Kode')
                    ->searchable(),
                TextColumn::make('warehouse.name')
                    ->label('Warehouse')
                    ->sortable(),
                TextColumn::make('quantity')
                    ->label('Stok Tersedia')
                    ->numeric()
                    ->sortable()
                    ->color(fn ($state) => $state < 10 ? 'danger' : ($state < 50 ? 'warning' : 'success')),
                TextColumn::make('reserved_quantity')
                    ->label('Reserved')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('available_quantity')
                    ->label('Tersedia')
                    ->numeric()
                    ->getStateUsing(fn ($record) => $record->quantity - $record->reserved_quantity)
                    ->color(fn ($state) => $state < 10 ? 'danger' : ($state < 50 ? 'warning' : 'success')),
            ])
            ->filters([
                SelectFilter::make('warehouse_id')
                    ->label('Filter Warehouse')
                    ->options(Warehouse::pluck('name', 'id')->toArray())
                    ->searchable()
                    ->preload(),
            ])
            ->defaultSort('quantity', 'asc');
    }
}
