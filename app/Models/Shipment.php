<?php

namespace App\Models;

use App\Services\NumberSeriesService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Shipment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'shipment_number',
        'sales_order_id',
        'warehouse_id',
        'shipped_date',
        'delivery_date',
        'status',
        'notes',
        'created_by',
    ];

    protected $casts = [
        'shipped_date' => 'date',
        'delivery_date' => 'date',
    ];

    protected static function booted(): void
    {
        static::creating(function ($sm) {
            if (empty($sm->shipment_number)) {
                $sm->shipment_number = NumberSeriesService::generate('SH', 'SH');
            }
            if (empty($sm->created_by) && auth()->check()) {
                $sm->created_by = auth()->id();
            }
        });
    }

    public function items(): HasMany
    {
        return $this->hasMany(ShipmentItem::class);
    }

    public function salesOrder(): BelongsTo
    {
        return $this->belongsTo(SalesOrder::class);
    }

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function createdByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
