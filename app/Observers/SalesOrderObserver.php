<?php

namespace App\Observers;

use App\Models\SalesOrder;
use App\Services\StockService;

class SalesOrderObserver
{
    public function __construct(protected StockService $stockService) {}

    /**
     * Handle the SalesOrder "updated" event.
     */
    public function updated(SalesOrder $salesOrder): void
    {
        // 1. Saat SO menjadi confirmed: reserve stock untuk setiap item
        if ($salesOrder->isDirty('status') && $salesOrder->status === 'confirmed') {
            $warehouseId = (int) $salesOrder->warehouse_id;

            foreach ($salesOrder->items as $item) {
                $qty = (float) $item->quantity;
                if ($qty > 0) {
                    $this->stockService->reserveStock((int) $item->product_id, $warehouseId, $qty);
                }
            }
        }

        // 2. Jika SO dibatalkan dari status confirmed: release stock yang direserve
        if ($salesOrder->isDirty('status') && $salesOrder->status === 'cancelled') {
            $warehouseId = (int) $salesOrder->warehouse_id;

            foreach ($salesOrder->items as $item) {
                $qty = (float) $item->quantity;
                if ($qty > 0) {
                    $this->stockService->releaseStock((int) $item->product_id, $warehouseId, $qty);
                }
            }
        }
    }
}
