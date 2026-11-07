<?php

namespace App\Filament\Staff\Resources\SalesOrders\Pages;

use App\Filament\Staff\Resources\SalesOrders\SalesOrderResource;
use Filament\Resources\Pages\CreateRecord;

class CreateSalesOrder extends CreateRecord
{
    protected static string $resource = SalesOrderResource::class;
}