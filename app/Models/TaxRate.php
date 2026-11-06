<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/** @mixin \Eloquent<\Database\Factories\TaxRateFactory> */
class TaxRate extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'code',
        'name',
        'rate',
        'type',
        'is_active',
    ];

    protected $casts = [
        'rate' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function calculate(float $amount): float
    {
        return match ($this->type) {
            'percent' => $amount * ($this->rate / 100),
            'amount' => $this->rate,
            default => 0,
        };
    }

    public function getDisplayNameAttribute(): string
    {
        return match ($this->type) {
            'percent' => "{$this->name} ({$this->rate}%)",
            'amount' => "{$this->name} ({$this->rate})",
            default => $this->name,
        };
    }
}