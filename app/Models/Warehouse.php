<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Warehouse extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'code',
        'address',
        'city',
        'state',
        'postal_code',
        'country',
        'phone',
        'notes',
        'is_active',
        'is_default',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_default' => 'boolean',
    ];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function stockBalances(): HasMany
    {
        return $this->hasMany(StockBalance::class);
    }

    public function stockMovements()
    {
        return $this->morphMany(StockMovement::class, 'reference');
    }

    protected static function booted(): void
    {
        static::creating(function (Warehouse $warehouse) {
            if (empty($warehouse->slug)) {
                $warehouse->slug = \Illuminate\Support\Str::slug($warehouse->name);
            }
            if (empty($warehouse->code)) {
                $warehouse->code = 'WH-' . strtoupper(\Illuminate\Support\Str::random(4));
            }
        });
    }
}