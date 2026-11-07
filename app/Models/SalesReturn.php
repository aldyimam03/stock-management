<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SalesReturn extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'return_number', 'shipment_id', 'warehouse_id',
        'return_date', 'status', 'created_by', 'notes',
    ];

    protected $casts = [
        'return_date' => 'date',
    ];

    protected static function booted(): void
    {
        static::saving(function ($sr) {
            if (empty($sr->return_number)) {
                $prefix = date('Y-SR');
                $count = static::count(['return_number LIKE' => $prefix . '%']) + 1;
                $sr->return_number = $prefix . '-' . strtoupper(fake()->unique()->numerify('######'));
            }
        });
    }

    public function shipment()
    {
        return $this->belongsTo(Shipment::class);
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