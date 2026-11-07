<?php

namespace App\Filament\Staff\Resources\PurchaseReturns;

use App\Filament\Staff\Resources\PurchaseReturns\Schemas\PurchaseReturnForm;
use App\Filament\Staff\Resources\PurchaseReturns\Tables\PurchaseReturnsTable;
use App\Models\PurchaseReturn;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class PurchaseReturnResource extends Resource
{
    protected static ?string $model = PurchaseReturn::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function getNavigationBadge(): ?string
    {
        return (string) PurchaseReturn::where('status', '!=', 'cancelled')->count();
    }

    public static function form(Schema $schema): Schema
    {
        return PurchaseReturnForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PurchaseReturnsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => \App\Filament\Staff\Resources\PurchaseReturns\Pages\ListPurchaseReturns::route('/'),
            'create' => \App\Filament\Staff\Resources\PurchaseReturns\Pages\CreatePurchaseReturn::route('/create'),
            'view' => \App\Filament\Staff\Resources\PurchaseReturns\Pages\ViewPurchaseReturn::route('/{record}'),
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