<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GoodsReceipt extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'receipt_number', 'purchase_order_id', 'warehouse_id', 'received_date',
        'status', 'created_by', 'notes',
    ];

    protected $casts = [
        'received_date' => 'date',
    ];

    protected static function booted(): void
    {
        static::saving(function ($gr) {
            if (empty($gr->receipt_number)) {
                $prefix = date('Y-GR');
                $count = static::count(['receipt_number LIKE' => $prefix . '%']) + 1;
                $gr->receipt_number = $prefix . '-' . strtoupper(fake()->unique()->numerify('######'));
            }
        });
    }

    public function purchaseOrder()
    {
        return $this->belongsTo(PurchaseOrder::class);
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function createdByUser()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
