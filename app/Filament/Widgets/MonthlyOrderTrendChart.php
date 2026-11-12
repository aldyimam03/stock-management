<?php

namespace App\Filament\Widgets;

use App\Models\PurchaseOrder;
use App\Models\SalesOrder;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;

class MonthlyOrderTrendChart extends ChartWidget
{
    protected ?string $heading = 'Tren Penjualan & Pembelian Bulanan';

    public function getDescription(): ?string
    {
        return 'Grafik 6 bulan terakhir total nilai transaksi penjualan vs pembelian';
    }

    protected function getData(): array
    {
        $months = collect(range(5, 0))->map(fn ($i) => Carbon::now()->subMonths($i));

        $labels = $months->map(fn ($m) => $m->format('M Y'))->toArray();

        $salesData = $months->map(function ($m) {
            return (float) SalesOrder::whereYear('created_at', $m->year)
                ->whereMonth('created_at', $m->month)
                ->where('status', '!=', 'cancelled')
                ->sum('total_amount');
        })->toArray();

        $purchaseData = $months->map(function ($m) {
            return (float) PurchaseOrder::whereYear('created_at', $m->year)
                ->whereMonth('created_at', $m->month)
                ->where('status', '!=', 'cancelled')
                ->sum('total_amount');
        })->toArray();

        return [
            'datasets' => [
                [
                    'label' => 'Total Penjualan (Rp)',
                    'data' => $salesData,
                    'borderColor' => '#10b981',
                    'backgroundColor' => 'rgba(16, 185, 129, 0.1)',
                    'fill' => true,
                    'tension' => 0.3,
                ],
                [
                    'label' => 'Total Pembelian (Rp)',
                    'data' => $purchaseData,
                    'borderColor' => '#3b82f6',
                    'backgroundColor' => 'rgba(59, 130, 246, 0.1)',
                    'fill' => true,
                    'tension' => 0.3,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
