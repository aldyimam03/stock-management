<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StockOpname extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'opname_number', 'warehouse_id', 'opname_date',
        'status', 'created_by', 'notes',
    ];

    protected $casts = [
        'opname_date' => 'date',
    ];

    protected static function booted(): void
    {
        static::saving(function ($so) {
            if (empty($so->opname_number)) {
                $prefix = date('Y-OP');
                $count = static::count(['opname_number LIKE' => $prefix . '%']) + 1;
                $so->opname_number = $prefix . '-' . strtoupper(fake()->unique()->numerify('######'));
            }
        });
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function createdByUser()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function items()
    {
        return $this->hasMany(StockOpnameItem::class);
    }
}