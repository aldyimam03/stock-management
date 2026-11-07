<?php

namespace App\Filament\Staff\Resources\Shipments\Pages;

use App\Filament\Staff\Resources\Shipments\ShipmentResource;
use Filament\Resources\Pages\ListRecords;

class ListShipments extends ListRecords
{
    protected static string $resource = ShipmentResource::class;
}