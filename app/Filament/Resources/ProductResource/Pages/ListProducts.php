<?php

namespace App\Filament\Resources\ProductResource\Pages;

use Filament\Actions;
use Filament\Actions\ImportAction;
use App\Filament\Exports\ProductExporter;
use App\Filament\Imports\ProductImporter;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\ProductResource;
use pxlrbt\FilamentExcel\Exports\ExcelExport;
use pxlrbt\FilamentExcel\Actions\Pages\ExportAction;
use pxlrbt\FilamentExcel\Columns\Column;

class ListProducts extends ListRecords
{
  protected static string $resource = ProductResource::class;

  protected function getHeaderActions(): array
  {
    return [
      Actions\CreateAction::make(),

    ExportAction::make()
      ->exports([
        ExcelExport::make('table')

        ->fromTable()->except([
          'image','status', 'created_at', 'updated_at', 'deleted_at',
        ]),
        ExcelExport::make('form')
            ->askForFilename()
            ->withFilename(fn ($filename) => 'Productos-' . $filename)

          ->withColumns([
            Column::make('bar_code')->heading('Código de Barra'),
            Column::make('name')->heading('Nombre'),
            Column::make('purchase_price')->heading('Precio Compra'),
            Column::make('sales_price')->heading('Precio Venta'),
            Column::make('stock')->heading('Stock'),
            Column::make('stock_minimum')->heading('Stock Mínimo'),
            Column::make('unit_measure')->heading('Unidad Medida'),
            Column::make('category.name')->heading('Categoría'),
            Column::make('status')->heading('Estado'),
            Column::make('expiration')->heading('Vencimiento'),
          ])
          ->withWriterType(\Maatwebsite\Excel\Excel::XLSX)
          ->withFilename(fn ($filename) => 'Productos-' . $filename),
      ])
      ->color('success')
      ->icon('ri-file-excel-2-fill'),


      // Actions\ExportAction::make()
      //     ->exporter(ProductExporter::class)
      //     ->outlined()
      //     ->color('success')
      //     ->icon('ri-file-excel-2-fill'),
      // ImportAction::make()
      //     ->importer(ProductImporter::class)
      //     ->outlined()
      //     ->color('info')
      //     ->icon('mdi-tray-arrow-up'),
    ];
  }
}
