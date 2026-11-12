<?php

namespace App\Models;

use App\Services\NumberSeriesService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class SalesReturn extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'return_number',
        'shipment_id',
        'warehouse_id',
        'return_date',
        'status',
        'created_by',
        'notes',
    ];

    protected $casts = [
        'return_date' => 'date',
    ];

    protected static function booted(): void
    {
        static::creating(function ($sr) {
            if (empty($sr->return_number)) {
                $sr->return_number = NumberSeriesService::generate('SR', 'SR');
            }
            if (empty($sr->created_by) && auth()->check()) {
                $sr->created_by = auth()->id();
            }
        });
    }

    public function items(): HasMany
    {
        return $this->hasMany(SalesReturnItem::class);
    }

    public function shipment(): BelongsTo
    {
        return $this->belongsTo(Shipment::class);
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
