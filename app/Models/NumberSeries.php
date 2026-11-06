<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/** @mixin \Eloquent<\Database\Factories\NumberSeriesFactory> */
class NumberSeries extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'prefix',
        'suffix',
        'next_number',
        'is_active',
    ];

    protected $casts = [
        'next_number' => 'integer',
        'is_active' => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function getNextNumber(): int
    {
        $this->increment('next_number');
        $this->refresh();
        return $this->next_number - 1;
    }

    public function generateNumber(): string
    {
        $number = $this->getNextNumber();
        $parts = [];
        if ($this->prefix) {
            $parts[] = $this->prefix;
        }
        $parts[] = str_pad((string) $number, 6, '0', STR_PAD_LEFT);
        if ($this->suffix) {
            $parts[] = $this->suffix;
        }
        return implode('-', $parts);
    }
}