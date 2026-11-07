<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PurchaseReturn extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'return_number', 'goods_receipt_id', 'warehouse_id',
        'return_date', 'status', 'created_by', 'notes',
    ];

    protected $casts = [
        'return_date' => 'date',
    ];

    protected static function booted(): void
    {
        static::saving(function ($pr) {
            if (empty($pr->return_number)) {
                $prefix = date('Y-PR');
                $count = static::count(['return_number LIKE' => $prefix . '%']) + 1;
                $pr->return_number = $prefix . '-' . strtoupper(fake()->unique()->numerify('######'));
            }
        });
    }

    public function goodsReceipt()
    {
        return $this->belongsTo(GoodsReceipt::class);
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