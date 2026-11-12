<?php

namespace App\Observers;

use App\Models\PurchaseReturn;
use App\Models\StockMovement;
use App\Services\StockService;

class PurchaseReturnObserver
{
    public function __construct(protected StockService $stockService) {}

    /**
     * Handle the PurchaseReturn "updated" event.
     */
    public function updated(PurchaseReturn $purchaseReturn): void
    {
        if ($purchaseReturn->isDirty('status') && $purchaseReturn->status === 'completed') {
            $alreadyProcessed = StockMovement::where('reference_type', PurchaseReturn::class)
                ->where('reference_id', $purchaseReturn->id)
                ->where('type', 'return')
                ->exists();

            if ($alreadyProcessed) {
                return;
            }

            $warehouseId = (int) $purchaseReturn->warehouse_id;

            foreach ($purchaseReturn->items as $item) {
                $qty = (float) $item->quantity;
                $unitPrice = (float) $item->unit_price;

                if ($qty > 0) {
                    $this->stockService->removeStock(
                        productId: (int) $item->product_id,
                        warehouseId: $warehouseId,
                        quantity: $qty,
                        unitPrice: $unitPrice,
                        reference: $purchaseReturn,
                        note: "Retur Pembelian (PR #{$purchaseReturn->return_number})",
                        userId: $purchaseReturn->created_by,
                        movementType: 'return'
                    );
                }
            }
        }
    }
}
