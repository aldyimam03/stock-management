<?php

namespace App\Models;

use App\Services\NumberSeriesService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class GoodsReceipt extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'receipt_number',
        'purchase_order_id',
        'warehouse_id',
        'received_date',
        'status',
        'created_by',
        'notes',
    ];

    protected $casts = [
        'received_date' => 'date',
    ];

    protected static function booted(): void
    {
        static::creating(function ($gr) {
            if (empty($gr->receipt_number)) {
                $gr->receipt_number = NumberSeriesService::generate('GR', 'GR');
            }
            if (empty($gr->created_by) && auth()->check()) {
                $gr->created_by = auth()->id();
            }
        });
    }

    public function items(): HasMany
    {
        return $this->hasMany(GoodsReceiptItem::class);
    }

    public function purchaseOrder(): BelongsTo
    {
        return $this->belongsTo(PurchaseOrder::class);
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
