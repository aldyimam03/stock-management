<?php

namespace App\Filament\Staff\Resources\StockOpnames;

use App\Filament\Staff\Resources\StockOpnames\Schemas\StockOpnameForm;
use App\Filament\Staff\Resources\StockOpnames\Tables\StockOpnamesTable;
use App\Models\StockOpname;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class StockOpnameResource extends Resource
{
    protected static ?string $model = StockOpname::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function getNavigationBadge(): ?string
    {
        return (string) StockOpname::where('status', '!=', 'cancelled')->count();
    }

    public static function form(Schema $schema): Schema
    {
        return StockOpnameForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return StockOpnamesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => \App\Filament\Staff\Resources\StockOpnames\Pages\ListStockOpnames::route('/'),
            'create' => \App\Filament\Staff\Resources\StockOpnames\Pages\CreateStockOpname::route('/create'),
            'view' => \App\Filament\Staff\Resources\StockOpnames\Pages\ViewStockOpname::route('/{record}'),
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