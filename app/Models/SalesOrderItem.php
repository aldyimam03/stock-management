<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SalesOrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'sales_order_id',
        'product_id',
        'quantity',
        'unit_price',
        'subtotal',
        'shipped_quantity',
        'notes',
    ];

    protected $casts = [
        'quantity' => 'decimal:4',
        'unit_price' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'shipped_quantity' => 'decimal:4',
    ];

    protected static function booted(): void
    {
        static::saving(function (SalesOrderItem $item) {
            $item->subtotal = round((float) $item->quantity * (float) $item->unit_price, 2);
        });

        static::saved(function (SalesOrderItem $item) {
            $item->salesOrder?->recalculateTotal();
        });

        static::deleted(function (SalesOrderItem $item) {
            $item->salesOrder?->recalculateTotal();
        });
    }

    public function salesOrder(): BelongsTo
    {
        return $this->belongsTo(SalesOrder::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
