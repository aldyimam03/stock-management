<?php

namespace App\Filament\Widgets;

use App\Models\StockBalance;
use App\Models\PurchaseOrder;
use App\Models\SalesOrder;
use App\Models\Product;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StockOverviewWidget extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Produk', Product::count())
                ->description('Total item di inventory')
                ->color('success'),
            Stat::make('Total Stock', StockBalance::sum('quantity') ?? 0)
                ->description('Jumlah total stok')
                ->color('primary'),
            Stat::make('PO Pending', PurchaseOrder::where('status', 'pending')->count())
                ->description('Purchase Order menunggu konfirmasi')
                ->color('warning'),
            Stat::make('SO Pending', SalesOrder::where('status', 'pending')->count())
                ->description('Sales Order menunggu pengiriman')
                ->color('info'),
        ];
    }
}
