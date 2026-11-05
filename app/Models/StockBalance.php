<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockBalance extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'warehouse_id',
        'quantity',
        'reserved_quantity',
        'average_cost',
    ];

    protected $casts = [
        'quantity' => 'decimal:4',
        'reserved_quantity' => 'decimal:4',
        'average_cost' => 'decimal:2',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function getAvailableQuantityAttribute(): float
    {
        return $this->quantity - $this->reserved_quantity;
    }
}
