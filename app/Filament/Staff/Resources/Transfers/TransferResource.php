<?php

namespace App\Filament\Staff\Resources\Transfers;

use App\Filament\Staff\Resources\Transfers\Schemas\TransferForm;
use App\Filament\Staff\Resources\Transfers\Tables\TransfersTable;
use App\Models\Transfer;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class TransferResource extends Resource
{
    protected static ?string $model = Transfer::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedArrowRightCircle;

    public static function getNavigationBadge(): ?string
    {
        return (string) Transfer::where('status', '!=', 'cancelled')->count();
    }

    public static function form(Schema $schema): Schema
    {
        return TransferForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TransfersTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => \App\Filament\Staff\Resources\Transfers\Pages\ListTransfers::route('/'),
            'create' => \App\Filament\Staff\Resources\Transfers\Pages\CreateTransfer::route('/create'),
            'view' => \App\Filament\Staff\Resources\Transfers\Pages\ViewTransfer::route('/{record}'),
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