<?php

namespace App\Filament\Staff\Resources\PurchaseOrders\Pages;

use App\Filament\Staff\Resources\PurchaseOrders\PurchaseOrderResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePurchaseOrder extends CreateRecord
{
    protected static string $resource = PurchaseOrderResource::class;
}