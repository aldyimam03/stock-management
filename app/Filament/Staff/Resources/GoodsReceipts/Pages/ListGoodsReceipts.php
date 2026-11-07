<?php

namespace App\Filament\Staff\Resources\GoodsReceipts\Pages;

use App\Filament\Staff\Resources\GoodsReceipts\GoodsReceiptResource;
use Filament\Resources\Pages\ListRecords;

class ListGoodsReceipts extends ListRecords
{
    protected static string $resource = GoodsReceiptResource::class;
}