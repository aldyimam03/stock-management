<?php

namespace App\Models;

use App\Services\NumberSeriesService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class PurchaseReturn extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'return_number',
        'goods_receipt_id',
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
        static::creating(function ($pr) {
            if (empty($pr->return_number)) {
                $pr->return_number = NumberSeriesService::generate('PR', 'PR');
            }
            if (empty($pr->created_by) && auth()->check()) {
                $pr->created_by = auth()->id();
            }
        });
    }

    public function items(): HasMany
    {
        return $this->hasMany(PurchaseReturnItem::class);
    }

    public function goodsReceipt(): BelongsTo
    {
        return $this->belongsTo(GoodsReceipt::class);
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
