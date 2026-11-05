<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Shipment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'shipment_number', 'sales_order_id', 'warehouse_id', 'shipped_date',
        'delivery_date', 'status', 'notes', 'created_by',
    ];

    protected $casts = [
        'shipped_date' => 'date',
        'delivery_date' => 'date',
    ];

    protected static function booted(): void
    {
        static::saving(function ($sm) {
            if (empty($sm->shipment_number)) {
                $prefix = date('Y-SHP');
                $count = static::count(['shipment_number LIKE' => $prefix . '%']) + 1;
                $sm->shipment_number = $prefix . '-' . strtoupper(fake()->unique()->numerify('######'));
            }
        });
    }

    public function salesOrder()
    {
        return $this->belongsTo(SalesOrder::class);
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
