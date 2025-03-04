<?php

namespace App\Filament\Resources\PurchaseResource\Pages;

use Filament\Actions;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\ViewRecord;
use App\Filament\Resources\PurchaseResource;
use Icetalker\FilamentTableRepeatableEntry\Infolists\Components\TableRepeatableEntry;

class ViewPurchase extends ViewRecord
{
    protected static string $resource = PurchaseResource::class;

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
                    ->schema([
                        Infolists\Components\TextEntry::make('user.name')
                            ->label('Usuario'),
                        Infolists\Components\TextEntry::make('supplier.name')
                            ->label('Proveedor'),
                        Infolists\Components\TextEntry::make('quote.number_quote')
                            ->label('Cotización')
                    ])->columns(3),
                Infolists\Components\Group::make()
                    ->columnSpanFull()
                    ->schema([
                        Infolists\Components\Group::make()
                            ->schema([
                                TableRepeatableEntry::make('detailparchuse')

                                    ->label('')

                                    ->schema([
                                        Infolists\Components\TextEntry::make('product_id')
                                            ->label('Descripción'),
                                        Infolists\Components\TextEntry::make('quantity')
                                            ->label('Cantidad'),

                                        Infolists\Components\TextEntry::make('unit_cost')
                                            ->label('Pre. Unitario'),

                                    ])
                                    ->striped()
                                    ->columnSpan('full'),
                            ])->columnSpan(9),
                        Infolists\Components\Card::make()
                            ->schema([
                                Infolists\Components\TextEntry::make('total')
                                    ->label('Total')
                                    ->prefix('S/.'),
                                Infolists\Components\TextEntry::make('purchase_number')
                                    ->label('N° Compra'),
                                Infolists\Components\IconEntry::make('status')
                                    ->label('estado')
                                    ->boolean(),
                            ])->columnSpan(3),
                    ])
                    ->columns(12),
            ]);
    }
}
