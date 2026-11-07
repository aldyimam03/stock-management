<?php

namespace App\Filament\Staff\Resources\SalesOrders\Pages;

use App\Filament\Staff\Resources\SalesOrders\SalesOrderResource;
use Filament\Resources\Pages\ViewRecord;

class ViewSalesOrder extends ViewRecord
{
    protected static string $resource = SalesOrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\Action::make('createShipment')
                ->label('Buat Pengiriman')
                ->icon('heroicon-o-truck')
                ->color('success')
                ->url(fn (): string => route('staff.shipments.create', ['sales_order' => $this->record->id])),
        ];
    }
}