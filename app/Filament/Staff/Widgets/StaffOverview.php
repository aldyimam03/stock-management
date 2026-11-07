<?php

namespace App\Filament\Staff\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\PurchaseOrder;
use App\Models\GoodsReceipt;
use App\Models\SalesOrder;
use App\Models\Shipment;
use App\Models\StockOpname;
use App\Models\Transfer;

class StaffOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('PO Pending', PurchaseOrder::where('status', 'pending')->count())
                ->description('Menunggu konfirmasi')
                ->descriptionIcon('heroicon-m-clock')
                ->color('warning')
                ->url(route('staff.purchase-orders.index', ['tableFilters[status][value]' => 'pending'])),

            Stat::make('Penerimaan Pending', GoodsReceipt::where('status', 'pending')->count())
                ->description('Barang belum diterima')
                ->descriptionIcon('heroicon-m-arrow-down-tray')
                ->color('info')
                ->url(route('staff.goods-receipts.index', ['tableFilters[status][value]' => 'pending'])),

            Stat::make('SO Pending', SalesOrder::where('status', 'pending')->count())
                ->description('Menunggu konfirmasi')
                ->descriptionIcon('heroicon-m-clock')
                ->color('warning')
                ->url(route('staff.sales-orders.index', ['tableFilters[status][value]' => 'pending'])),

            Stat::make('Pengiriman Pending', Shipment::where('status', 'pending')->count())
                ->description('Belum dikirim')
                ->descriptionIcon('heroicon-m-truck')
                ->color('info')
                ->url(route('staff.shipments.index', ['tableFilters[status][value]' => 'pending'])),

            Stat::make('Transfer In Transit', Transfer::where('status', 'in_transit')->count())
                ->description('Sedang dalam perjalanan')
                ->descriptionIcon('heroicon-m-arrow-right-circle')
                ->color('primary')
                ->url(route('staff.transfers.index', ['tableFilters[status][value]' => 'in_transit'])),

            Stat::make('Opname Progress', StockOpname::where('status', 'in_progress')->count())
                ->description('Sedang dihitung')
                ->descriptionIcon('heroicon-m-bar-chart')
                ->color('warning')
                ->url(route('staff.stock-opnames.index', ['tableFilters[status][value]' => 'in_progress'])),
        ];
    }
}