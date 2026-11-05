<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Supplier extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'code',
        'email',
        'phone',
        'address',
        'city',
        'state',
        'postal_code',
        'country',
        'notes',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function stockMovements()
    {
        return $this->morphMany(StockMovement::class, 'reference');
    }

    protected static function booted(): void
    {
        static::creating(function (Supplier $supplier) {
            if (empty($supplier->slug)) {
                $supplier->slug = \Illuminate\Support\Str::slug($supplier->name);
            }
            if (empty($supplier->code)) {
                $supplier->code = 'SUP-' . strtoupper(\Illuminate\Support\Str::random(6));
            }
        });
    }
}
