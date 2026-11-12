<?php

namespace App\Services;

use App\Models\NumberSeries;
use Illuminate\Support\Facades\DB;

class NumberSeriesService
{
    /**
     * Generate sequential document number safely using database locks.
     */
    public static function generate(string $code, ?string $fallbackPrefix = null): string
    {
        return DB::transaction(function () use ($code, $fallbackPrefix) {
            $series = NumberSeries::where('code', $code)->lockForUpdate()->first();

            if (! $series) {
                $prefix = $fallbackPrefix ?? $code;
                $series = NumberSeries::create([
                    'code' => $code,
                    'name' => "{$code} Series",
                    'prefix' => $prefix,
                    'next_number' => 1,
                    'is_active' => true,
                ]);
            }

            $currentNumber = $series->next_number;
            $series->increment('next_number');

            $parts = [];
            if ($series->prefix) {
                $parts[] = $series->prefix;
            }
            $parts[] = str_pad((string) $currentNumber, 6, '0', STR_PAD_LEFT);
            if ($series->suffix) {
                $parts[] = $series->suffix;
            }

            return implode('-', $parts);
        });
    }
}
