<?php

namespace App\Models;

use App\Services\NumberSeriesService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class SalesOrder extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'order_number',
        'customer_id',
        'warehouse_id',
        'order_date',
        'expected_delivery_date',
        'status',
        'total_amount',
        'created_by',
        'notes',
    ];

    protected $casts = [
        'order_date' => 'date',
        'expected_delivery_date' => 'date',
        'total_amount' => 'decimal:2',
    ];

    protected static function booted(): void
    {
        static::creating(function (SalesOrder $so) {
            if (empty($so->order_number)) {
                $so->order_number = NumberSeriesService::generate('SO', 'SO');
            }
            if (empty($so->created_by) && auth()->check()) {
                $so->created_by = auth()->id();
            }
        });
    }

    public function recalculateTotal(): void
    {
        $total = $this->items()->sum('subtotal');
        $this->updateQuietly(['total_amount' => $total]);
    }

    public function items(): HasMany
    {
        return $this->hasMany(SalesOrderItem::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function shipments(): HasMany
    {
        return $this->hasMany(Shipment::class);
    }

    public function createdByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
