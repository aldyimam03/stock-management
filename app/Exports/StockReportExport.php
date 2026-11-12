<?php

namespace App\Exports;

use App\Models\StockBalance;
use Illuminate\Support\Enumerable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class StockReportExport implements FromCollection, WithHeadings, WithMapping
{
    public function __construct(protected ?int $warehouseId = null) {}

    public function collection(): Enumerable
    {
        return StockBalance::query()
            ->when($this->warehouseId, fn ($q) => $q->where('warehouse_id', $this->warehouseId))
            ->with(['product', 'warehouse'])
            ->get();
    }

    public function headings(): array
    {
        return [
            'Kode Produk',
            'Nama Produk',
            'Warehouse',
            'Stok Fisik',
            'Stok Reserved',
            'Stok Tersedia',
            'Harga Rata-rata (Rp)',
            'Nilai Total Stok (Rp)',
        ];
    }

    /**
     * @param  StockBalance  $row
     */
    public function map($row): array
    {
        $qty = (float) $row->quantity;
        $reserved = (float) $row->reserved_quantity;
        $available = $qty - $reserved;
        $avgCost = (float) $row->average_cost;

        return [
            $row->product?->slug ?? '-',
            $row->product?->name ?? '-',
            $row->warehouse?->name ?? '-',
            $qty,
            $reserved,
            $available,
            $avgCost,
            round($qty * $avgCost, 2),
        ];
    }
}
