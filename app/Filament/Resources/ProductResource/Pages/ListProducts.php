<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Filament\Exports\ProductExporter;
use App\Filament\Imports\ProductImporter;
use App\Filament\Resources\ProductResource;
use Filament\Actions;
use Filament\Actions\ImportAction;
use Filament\Resources\Pages\ListRecords;

class ListProducts extends ListRecords
{
    protected static string $resource = ProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            Actions\ExportAction::make()
                ->exporter(ProductExporter::class)
                ->outlined()
                ->color('success')
                ->icon('ri-file-excel-2-fill'),
            ImportAction::make()
                ->importer(ProductImporter::class)
                ->outlined()
                ->color('info')
                ->icon('mdi-tray-arrow-up'),
        ];
    }
}
