<?php

namespace App\Filament\Resources\ProductResource\Pages;

use Filament\Actions;
use Filament\Infolists\Infolist;
use Filament\Infolists;
use Filament\Resources\Pages\ViewRecord;
use App\Filament\Resources\ProductResource;

class ViewProduct extends ViewRecord
{
  protected static string $resource = ProductResource::class;

  protected function getHeaderActions(): array
  {
    return [
      Actions\EditAction::make(),
    ];
  }
  public function infolist(Infolist $infolist): Infolist
  {
    return $infolist
      ->schema([
        Infolists\Components\Card::make()
          ->columns([
            'default' => 1,
            'sm' => 1,
            'md' => 2,
            'lg' => 4,
            'xl' => 4,
            '2xl' => 4,
          ])
          ->schema([
            Infolists\Components\Card::make()
              ->schema([
                Infolists\Components\ImageEntry::make('image')
                  ->label('Foto')
                  ->extraAttributes([
                    'class' => 'aspect-[3/2] object-contain rounded-lg',
                ])
                ->disk('public')

              ])
              ->columnSpan([
                'default' => 'full',
                'sm' => 'full',
                'md' => 1,
                'lg' => 1,
                'xl' => 1,
                '2xl' => 1,
              ])
              ->extraAttributes([
                'class' => 'flex items-center justify-center p-4',
              ]),
            Infolists\Components\Card::make()
              ->schema([
                Infolists\Components\Grid::make()
                  ->columns([
                    'default' => 1,
                    'sm' => 1,
                    'md' => 2,
                    'lg' => 2,
                    'xl' => 2,
                    '2xl' => 2,
                  ])
                  ->schema([
                    Infolists\Components\TextEntry::make('bar_code')
                      ->label('Codigo barra'),
                    Infolists\Components\TextEntry::make('name')
                      ->label('Nombre'),
                    Infolists\Components\TextEntry::make('purchase_price')
                      ->label('Precio de compra'),
                    Infolists\Components\TextEntry::make('sales_price')
                      ->label('Precio de venta'),
                  ]),
              ])
              ->columnSpan([
                'default' => 1,
                'sm' => 1,
                'md' => 1,
                'lg' => 3,
                'xl' => 3,
                '2xl' => 3,
              ]),
          ]),

        Infolists\Components\Card::make()
          ->columns([
            'default' => 1,
            'sm' => 1,
            'md' => 2,
            'lg' => 2,
            'xl' => 2,
            '2xl' => 2,
          ])
          ->schema([
            Infolists\Components\TextEntry::make('stock')
              ->label('Stock'),
            Infolists\Components\TextEntry::make('stock_minimum')
              ->label('Stock minimo'),
            Infolists\Components\TextEntry::make('unit_measure')
              ->label('Unidad de medida'),
            Infolists\Components\TextEntry::make('category_id')
              ->label('Categoria'),
            Infolists\Components\TextEntry::make('status')
              ->label('Estado'),
            Infolists\Components\TextEntry::make('expiration')
              ->label('Fecha de vencimiento'),
          ]),
      ]);
  }
}
