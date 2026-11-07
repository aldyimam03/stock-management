<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StockOpnameItem extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'stock_opname_id', 'product_id',
        'system_quantity', 'physical_quantity', 'difference',
        'uom', 'notes',
    ];

    protected $casts = [
        'system_quantity' => 'decimal:4',
        'physical_quantity' => 'decimal:4',
        'difference' => 'decimal:4',
    ];

    public function stockOpname()
    {
        return $this->belongsTo(StockOpname::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}