<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
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
        'credit_limit',
        'balance',
        'notes',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'credit_limit' => 'decimal:2',
        'balance' => 'decimal:2',
    ];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function orders(): HasMany
    {
        return $this->hasMany(SalesOrder::class);
    }

    protected static function booted(): void
    {
        static::creating(function (Customer $customer) {
            if (empty($customer->slug)) {
                $customer->slug = \Illuminate\Support\Str::slug($customer->name);
            }
            if (empty($customer->code)) {
                $customer->code = 'CST-' . strtoupper(\Illuminate\Support\Str::random(6));
            }
        });
    }
}