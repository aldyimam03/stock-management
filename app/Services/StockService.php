<?php

namespace App\Services;

use App\Models\Product;
use App\Models\StockBalance;
use App\Models\StockMovement;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class StockService
{
    /**
     * Add stock to warehouse (e.g., from Goods Receipt, Initial Stock, Purchase, In-Transfer).
     */
    public function addStock(
        int $productId,
        int $warehouseId,
        float $quantity,
        float $unitPrice = 0,
        ?Model $reference = null,
        ?string $note = null,
        ?int $userId = null,
        string $movementType = 'in'
    ): StockMovement {
        if ($quantity <= 0) {
            throw new InvalidArgumentException('Kuantitas penambahan stok harus lebih besar dari 0.');
        }

        return DB::transaction(function () use ($productId, $warehouseId, $quantity, $unitPrice, $reference, $note, $userId, $movementType) {
            $balance = StockBalance::firstOrCreate(
                ['product_id' => $productId, 'warehouse_id' => $warehouseId],
                ['quantity' => 0, 'reserved_quantity' => 0, 'average_cost' => 0]
            );

            // Re-lock for update
            $balance = StockBalance::where('id', $balance->id)->lockForUpdate()->first();

            $oldQty = (float) $balance->quantity;
            $oldCost = (float) $balance->average_cost;
            $newQty = $oldQty + $quantity;

            // Hitung weighted average cost
            $newAvgCost = $newQty > 0
                ? (($oldQty * $oldCost) + ($quantity * $unitPrice)) / $newQty
                : $unitPrice;

            $balance->update([
                'quantity' => $newQty,
                'average_cost' => round($newAvgCost, 2),
            ]);

            $this->syncProductStock($productId);

            return StockMovement::create([
                'product_id' => $productId,
                'warehouse_id' => $warehouseId,
                'type' => $movementType,
                'quantity' => $quantity,
                'unit_price' => $unitPrice,
                'total_value' => round($quantity * $unitPrice, 2),
                'reference_type' => $reference ? get_class($reference) : null,
                'reference_id' => $reference?->getKey(),
                'note' => $note,
                'created_by' => $userId,
            ]);
        });
    }

    /**
     * Deduct stock from warehouse (e.g., from Shipment, Out-Transfer).
     */
    public function removeStock(
        int $productId,
        int $warehouseId,
        float $quantity,
        float $unitPrice = 0,
        ?Model $reference = null,
        ?string $note = null,
        ?int $userId = null,
        string $movementType = 'out',
        bool $releaseReserved = false
    ): StockMovement {
        if ($quantity <= 0) {
            throw new InvalidArgumentException('Kuantitas pengurangan stok harus lebih besar dari 0.');
        }

        return DB::transaction(function () use ($productId, $warehouseId, $quantity, $unitPrice, $reference, $note, $userId, $movementType, $releaseReserved) {
            $balance = StockBalance::where('product_id', $productId)
                ->where('warehouse_id', $warehouseId)
                ->lockForUpdate()
                ->first();

            if (! $balance) {
                $balance = StockBalance::create([
                    'product_id' => $productId,
                    'warehouse_id' => $warehouseId,
                    'quantity' => 0,
                    'reserved_quantity' => 0,
                    'average_cost' => 0,
                ]);
            }

            $newQty = (float) $balance->quantity - $quantity;
            $newReserved = (float) $balance->reserved_quantity;

            if ($releaseReserved) {
                $newReserved = max(0, $newReserved - $quantity);
            }

            $effectivePrice = $unitPrice > 0 ? $unitPrice : (float) $balance->average_cost;

            $balance->update([
                'quantity' => $newQty,
                'reserved_quantity' => $newReserved,
            ]);

            $this->syncProductStock($productId);

            return StockMovement::create([
                'product_id' => $productId,
                'warehouse_id' => $warehouseId,
                'type' => $movementType,
                'quantity' => $quantity,
                'unit_price' => $effectivePrice,
                'total_value' => round($quantity * $effectivePrice, 2),
                'reference_type' => $reference ? get_class($reference) : null,
                'reference_id' => $reference?->getKey(),
                'note' => $note,
                'created_by' => $userId,
            ]);
        });
    }

    /**
     * Reserve stock for sales orders.
     */
    public function reserveStock(int $productId, int $warehouseId, float $quantity): void
    {
        if ($quantity <= 0) {
            return;
        }

        DB::transaction(function () use ($productId, $warehouseId, $quantity) {
            $balance = StockBalance::firstOrCreate(
                ['product_id' => $productId, 'warehouse_id' => $warehouseId],
                ['quantity' => 0, 'reserved_quantity' => 0, 'average_cost' => 0]
            );

            $balance = StockBalance::where('id', $balance->id)->lockForUpdate()->first();
            $balance->increment('reserved_quantity', $quantity);
        });
    }

    /**
     * Release previously reserved stock.
     */
    public function releaseStock(int $productId, int $warehouseId, float $quantity): void
    {
        if ($quantity <= 0) {
            return;
        }

        DB::transaction(function () use ($productId, $warehouseId, $quantity) {
            $balance = StockBalance::where('product_id', $productId)
                ->where('warehouse_id', $warehouseId)
                ->lockForUpdate()
                ->first();

            if ($balance) {
                $newReserved = max(0, (float) $balance->reserved_quantity - $quantity);
                $balance->update(['reserved_quantity' => $newReserved]);
            }
        });
    }

    /**
     * Adjust stock directly (e.g., from Stock Opname).
     */
    public function adjustStock(
        int $productId,
        int $warehouseId,
        float $actualQuantity,
        ?Model $reference = null,
        ?string $note = null,
        ?int $userId = null
    ): StockMovement {
        return DB::transaction(function () use ($productId, $warehouseId, $actualQuantity, $reference, $note, $userId) {
            $balance = StockBalance::firstOrCreate(
                ['product_id' => $productId, 'warehouse_id' => $warehouseId],
                ['quantity' => 0, 'reserved_quantity' => 0, 'average_cost' => 0]
            );

            $balance = StockBalance::where('id', $balance->id)->lockForUpdate()->first();
            $diff = $actualQuantity - (float) $balance->quantity;

            $balance->update(['quantity' => $actualQuantity]);
            $this->syncProductStock($productId);

            return StockMovement::create([
                'product_id' => $productId,
                'warehouse_id' => $warehouseId,
                'type' => 'adjustment',
                'quantity' => $diff,
                'unit_price' => (float) $balance->average_cost,
                'total_value' => round(abs($diff) * (float) $balance->average_cost, 2),
                'reference_type' => $reference ? get_class($reference) : null,
                'reference_id' => $reference?->getKey(),
                'note' => $note ?? 'Penyesuaian stok opname',
                'created_by' => $userId,
            ]);
        });
    }

    /**
     * Sinkronkan total stock_quantity di products dari stock_balances
     */
    public function syncProductStock(int $productId): void
    {
        $total = (float) StockBalance::where('product_id', $productId)->sum('quantity');

        Product::where('id', $productId)->update([
            'stock_quantity' => (int) round($total),
        ]);
    }
}
