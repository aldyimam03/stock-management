<?php

namespace App\Observers;

use App\Models\SalesOrderItem;
use App\Models\Shipment;
use App\Models\StockMovement;
use App\Services\StockService;

class ShipmentObserver
{
    public function __construct(protected StockService $stockService) {}

    /**
     * Handle the Shipment "updated" event.
     */
    public function updated(Shipment $shipment): void
    {
        if ($shipment->isDirty('status') && in_array($shipment->status, ['shipped', 'delivered'])) {
            $existing = StockMovement::where('reference_type', Shipment::class)
                ->where('reference_id', $shipment->id)
                ->where('type', 'out')
                ->exists();

            if ($existing) {
                return;
            }

            $warehouseId = (int) $shipment->warehouse_id;
            $items = $shipment->items()->count() > 0 ? $shipment->items : ($shipment->salesOrder?->items ?? collect());

            foreach ($items as $item) {
                $qty = (float) $item->quantity;
                $unitPrice = (float) $item->unit_price;

                if ($qty > 0) {
                    $this->stockService->removeStock(
                        productId: (int) $item->product_id,
                        warehouseId: $warehouseId,
                        quantity: $qty,
                        unitPrice: $unitPrice,
                        reference: $shipment,
                        note: "Pengiriman Penjualan (SH #{$shipment->shipment_number})",
                        userId: $shipment->created_by,
                        movementType: 'out',
                        releaseReserved: true
                    );

                    if ($shipment->sales_order_id) {
                        $soItem = SalesOrderItem::where('sales_order_id', $shipment->sales_order_id)
                            ->where('product_id', $item->product_id)
                            ->first();

                        $soItem?->increment('shipped_quantity', $qty);
                    }
                }
            }

            if ($shipment->salesOrder) {
                $shipment->salesOrder->updateQuietly([
                    'status' => $shipment->status === 'delivered' ? 'delivered' : 'shipped',
                ]);
            }
        }
    }
}
