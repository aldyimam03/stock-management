<?php

namespace App\Filament\Staff\Resources\SalesReturns;

use App\Filament\Staff\Resources\SalesReturns\Schemas\SalesReturnForm;
use App\Filament\Staff\Resources\SalesReturns\Tables\SalesReturnsTable;
use App\Models\SalesReturn;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class SalesReturnResource extends Resource
{
    protected static ?string $model = SalesReturn::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function getNavigationBadge(): ?string
    {
        return (string) SalesReturn::where('status', '!=', 'cancelled')->count();
    }

    public static function form(Schema $schema): Schema
    {
        return SalesReturnForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SalesReturnsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => \App\Filament\Staff\Resources\SalesReturns\Pages\ListSalesReturns::route('/'),
            'create' => \App\Filament\Staff\Resources\SalesReturns\Pages\CreateSalesReturn::route('/create'),
            'view' => \App\Filament\Staff\Resources\SalesReturns\Pages\ViewSalesReturn::route('/{record}'),
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