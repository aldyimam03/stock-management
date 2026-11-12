<?php

namespace App\Observers;

use App\Models\SalesReturn;
use App\Models\StockMovement;
use App\Services\StockService;

class SalesReturnObserver
{
    public function __construct(protected StockService $stockService) {}

    /**
     * Handle the SalesReturn "updated" event.
     */
    public function updated(SalesReturn $salesReturn): void
    {
        if ($salesReturn->isDirty('status') && $salesReturn->status === 'completed') {
            $alreadyProcessed = StockMovement::where('reference_type', SalesReturn::class)
                ->where('reference_id', $salesReturn->id)
                ->where('type', 'return')
                ->exists();

            if ($alreadyProcessed) {
                return;
            }

            $warehouseId = (int) $salesReturn->warehouse_id;

            foreach ($salesReturn->items as $item) {
                $qty = (float) $item->quantity;
                $unitPrice = (float) $item->unit_price;

                if ($qty > 0) {
                    $this->stockService->addStock(
                        productId: (int) $item->product_id,
                        warehouseId: $warehouseId,
                        quantity: $qty,
                        unitPrice: $unitPrice,
                        reference: $salesReturn,
                        note: "Retur Penjualan (SR #{$salesReturn->return_number})",
                        userId: $salesReturn->created_by,
                        movementType: 'return'
                    );
                }
            }
        }
    }
}
