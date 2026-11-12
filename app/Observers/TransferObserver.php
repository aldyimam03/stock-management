<?php

namespace App\Observers;

use App\Models\StockMovement;
use App\Models\Transfer;
use App\Services\StockService;

class TransferObserver
{
    public function __construct(protected StockService $stockService) {}

    /**
     * Handle the Transfer "updated" event.
     */
    public function updated(Transfer $transfer): void
    {
        if ($transfer->isDirty('status') && $transfer->status === 'completed') {
            $alreadyProcessed = StockMovement::where('reference_type', Transfer::class)
                ->where('reference_id', $transfer->id)
                ->exists();

            if ($alreadyProcessed) {
                return;
            }

            $fromWarehouse = (int) $transfer->from_warehouse_id;
            $toWarehouse = (int) $transfer->to_warehouse_id;

            foreach ($transfer->items as $item) {
                $qty = (float) $item->quantity;
                if ($qty <= 0) {
                    continue;
                }

                $productId = (int) $item->product_id;

                // 1. Kurangi stok di Gudang Asal
                $this->stockService->removeStock(
                    productId: $productId,
                    warehouseId: $fromWarehouse,
                    quantity: $qty,
                    reference: $transfer,
                    note: "Transfer Keluar #{$transfer->id} ke Gudang {$transfer->toWarehouse?->name}",
                    userId: $transfer->completed_by ?? $transfer->created_by,
                    movementType: 'transfer'
                );

                // 2. Tambah stok di Gudang Tujuan
                $this->stockService->addStock(
                    productId: $productId,
                    warehouseId: $toWarehouse,
                    quantity: $qty,
                    reference: $transfer,
                    note: "Transfer Masuk #{$transfer->id} dari Gudang {$transfer->fromWarehouse?->name}",
                    userId: $transfer->completed_by ?? $transfer->created_by,
                    movementType: 'transfer'
                );

                $item->updateQuietly(['received_quantity' => $qty]);
            }
        }
    }
}
