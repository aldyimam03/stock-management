<?php

namespace App\Filament\Staff\Resources\PurchaseOrders;

use App\Filament\Staff\Resources\PurchaseOrders\Schemas\PurchaseOrderForm;
use App\Filament\Staff\Resources\PurchaseOrders\Tables\PurchaseOrdersTable;
use App\Models\PurchaseOrder;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class PurchaseOrderResource extends Resource
{
    protected static ?string $model = PurchaseOrder::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function getNavigationBadge(): ?string
    {
        return (string) PurchaseOrder::where('status', '!=', 'cancelled')->count();
    }

    public static function form(Schema $schema): Schema
    {
        return PurchaseOrderForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PurchaseOrdersTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => \App\Filament\Staff\Resources\PurchaseOrders\Pages\ListPurchaseOrders::route('/'),
            'create' => \App\Filament\Staff\Resources\PurchaseOrders\Pages\CreatePurchaseOrder::route('/create'),
            'view' => \App\Filament\Staff\Resources\PurchaseOrders\Pages\ViewPurchaseOrder::route('/{record}'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                \Illuminate\Database\Eloquent\SoftDeletingScope::class,
            ]);
    }
}