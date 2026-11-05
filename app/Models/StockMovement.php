<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class StockMovement extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'warehouse_id',
        'supplier_id',
        'customer_id',
        'type',
        'quantity',
        'unit_price',
        'total_value',
        'reference_type',
        'reference_id',
        'note',
        'created_by',
    ];

    protected $casts = [
        'quantity' => 'decimal:4',
        'unit_price' => 'decimal:2',
        'total_value' => 'decimal:2',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function reference(): MorphTo
    {
        return $this->morphTo();
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
