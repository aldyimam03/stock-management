<?php

namespace App\Observers;

use App\Models\GoodsReceipt;
use App\Models\PurchaseOrderItem;
use App\Services\StockService;

class GoodsReceiptObserver
{
    public function __construct(protected StockService $stockService) {}

    /**
     * Handle the GoodsReceipt "updated" event.
     */
    public function updated(GoodsReceipt $goodsReceipt): void
    {
        // Deteksi perubahan status menjadi completed
        if ($goodsReceipt->isDirty('status') && $goodsReceipt->status === 'completed') {
            $warehouseId = (int) $goodsReceipt->warehouse_id;
            $items = $goodsReceipt->items()->count() > 0 ? $goodsReceipt->items : ($goodsReceipt->purchaseOrder?->items ?? collect());

            foreach ($items as $item) {
                $qty = (float) $item->quantity;
                $unitPrice = (float) $item->unit_price;

                if ($qty > 0) {
                    $this->stockService->addStock(
                        productId: (int) $item->product_id,
                        warehouseId: $warehouseId,
                        quantity: $qty,
                        unitPrice: $unitPrice,
                        reference: $goodsReceipt,
                        note: "Penerimaan Barang (GR #{$goodsReceipt->receipt_number})",
                        userId: $goodsReceipt->created_by,
                        movementType: 'in'
                    );

                    // Jika ada PO terkait, perbarui received_quantity item PO
                    if ($goodsReceipt->purchase_order_id) {
                        $poItem = PurchaseOrderItem::where('purchase_order_id', $goodsReceipt->purchase_order_id)
                            ->where('product_id', $item->product_id)
                            ->first();

                        $poItem?->increment('received_quantity', $qty);
                    }
                }
            }

            if ($goodsReceipt->purchaseOrder) {
                $goodsReceipt->purchaseOrder->updateQuietly(['status' => 'received']);
            }
        }
    }
}
