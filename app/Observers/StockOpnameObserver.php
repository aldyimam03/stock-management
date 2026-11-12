<?php

namespace App\Observers;

use App\Models\StockMovement;
use App\Models\StockOpname;
use App\Services\StockService;

class StockOpnameObserver
{
    public function __construct(protected StockService $stockService) {}

    /**
     * Handle the StockOpname "updated" event.
     */
    public function updated(StockOpname $stockOpname): void
    {
        if ($stockOpname->isDirty('status') && $stockOpname->status === 'completed') {
            $alreadyProcessed = StockMovement::where('reference_type', StockOpname::class)
                ->where('reference_id', $stockOpname->id)
                ->where('type', 'adjustment')
                ->exists();

            if ($alreadyProcessed) {
                return;
            }

            $warehouseId = (int) $stockOpname->warehouse_id;

            foreach ($stockOpname->items as $item) {
                $actualQty = (float) $item->physical_quantity;

                $this->stockService->adjustStock(
                    productId: (int) $item->product_id,
                    warehouseId: $warehouseId,
                    actualQuantity: $actualQty,
                    reference: $stockOpname,
                    note: "Penyesuaian Opname (OP #{$stockOpname->opname_number})",
                    userId: $stockOpname->created_by
                );
            }
        }
    }
}
