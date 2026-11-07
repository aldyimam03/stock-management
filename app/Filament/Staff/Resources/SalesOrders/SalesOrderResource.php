<?php

namespace App\Filament\Staff\Resources\SalesOrders;

use App\Filament\Staff\Resources\SalesOrders\Schemas\SalesOrderForm;
use App\Filament\Staff\Resources\SalesOrders\Tables\SalesOrdersTable;
use App\Models\SalesOrder;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class SalesOrderResource extends Resource
{
    protected static ?string $model = SalesOrder::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function getNavigationBadge(): ?string
    {
        return (string) SalesOrder::where('status', '!=', 'cancelled')->count();
    }

    public static function form(Schema $schema): Schema
    {
        return SalesOrderForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SalesOrdersTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => \App\Filament\Staff\Resources\SalesOrders\Pages\ListSalesOrders::route('/'),
            'create' => \App\Filament\Staff\Resources\SalesOrders\Pages\CreateSalesOrder::route('/create'),
            'view' => \App\Filament\Staff\Resources\SalesOrders\Pages\ViewSalesOrder::route('/{record}'),
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