<?php

namespace App\Filament\Staff\Resources\GoodsReceipts;

use App\Filament\Staff\Resources\GoodsReceipts\Schemas\GoodsReceiptForm;
use App\Filament\Staff\Resources\GoodsReceipts\Tables\GoodsReceiptsTable;
use App\Models\GoodsReceipt;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class GoodsReceiptResource extends Resource
{
    protected static ?string $model = GoodsReceipt::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function getNavigationBadge(): ?string
    {
        return (string) GoodsReceipt::where('status', '!=', 'cancelled')->count();
    }

    public static function form(Schema $schema): Schema
    {
        return GoodsReceiptForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return GoodsReceiptsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => \App\Filament\Staff\Resources\GoodsReceipts\Pages\ListGoodsReceipts::route('/'),
            'create' => \App\Filament\Staff\Resources\GoodsReceipts\Pages\CreateGoodsReceipt::route('/create'),
            'view' => \App\Filament\Staff\Resources\GoodsReceipts\Pages\ViewGoodsReceipt::route('/{record}'),
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