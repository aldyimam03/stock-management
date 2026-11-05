<?php

namespace App\Filament\Widgets;

use App\Models\PurchaseOrder;
use App\Models\SalesOrder;
use App\Models\GoodsReceipt;
use App\Models\Shipment;
use Filament\Widgets\ChartWidget;

class PendingOrdersWidget extends ChartWidget
{
    protected ?string $heading = 'Pesanan Pending';
    protected ?string $description = 'Ringkasan pesanan yang masih pending';

    public function getDescription(): ?string
    {
        return $this->description;
    }

    protected function getData(): array
    {
        $pendingPO = PurchaseOrder::where('status', 'pending')->count();
        $pendingSO = SalesOrder::where('status', 'pending')->count();
        $pendingGR = GoodsReceipt::where('status', 'pending')->count();
        $pendingShipment = Shipment::where('status', 'pending')->count();

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Pesanan',
                    'data' => [$pendingPO, $pendingSO, $pendingGR, $pendingShipment],
                    'backgroundColor' => ['#f59e0b', '#3b82f6', '#10b981', '#6366f1'],
                    'borderColor' => ['#f59e0b', '#3b82f6', '#10b981', '#6366f1'],
                    'borderWidth' => 1,
                ],
            ],
            'labels' => ['PO Pending', 'SO Pending', 'GR Pending', 'Shipment Pending'],
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}