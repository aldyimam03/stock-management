<?php

namespace App\Filament\Staff\Resources\PurchaseOrders\Pages;

use App\Filament\Staff\Resources\PurchaseOrders\PurchaseOrderResource;
use Filament\Resources\Pages\ViewRecord;

class ViewPurchaseOrder extends ViewRecord
{
    protected static string $resource = PurchaseOrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\Action::make('createGoodsReceipt')
                ->label('Buat Penerimaan Barang')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('success')
                ->url(fn (): string => route('staff.goods-receipts.create', ['purchase_order' => $this->record->id])),
        ];
    }
}