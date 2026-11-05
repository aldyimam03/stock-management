<?php

namespace App\Filament\Resources\Products\Pages;

use App\Filament\Resources\Products\ProductResource;
use Filament\Actions\CreateAction;
use Filament\Actions\ExportAction;
use Filament\Actions\ImportAction;
use Filament\Resources\Pages\ListRecords;

class ListProducts extends ListRecords
{
    protected static string $resource = ProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
            ExportAction::make()
                ->exporter(\App\Filament\Resources\Products\Exporters\ProductExporter::class)
                ->label('Ekspor Produk'),
            ImportAction::make()
                ->importer(\App\Filament\Resources\Products\Importers\ProductImporter::class)
                ->label('Impor Produk'),
        ];
    }
}
