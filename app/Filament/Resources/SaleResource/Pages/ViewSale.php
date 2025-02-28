<?php

namespace App\Filament\Resources\SaleResource\Pages;

use App\Filament\Resources\SaleResource;
use Filament\Actions;
use Filament\Infolists\Components\Card;
use Filament\Infolists\Components\Grid;
use Filament\Infolists\Components\Group;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Pages\Actions\Action;
use Filament\Resources\Pages\ViewRecord;
use Icetalker\FilamentTableRepeatableEntry\Infolists\Components\TableRepeatableEntry;
use Pelmered\FilamentMoneyField\Infolists\Components\MoneyEntry;

class ViewSale extends ViewRecord
{
    protected static string $resource = SaleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
            Action::make('print')
                ->label('Print')
                ->icon('solar-printer-outline')
                ->outlined()
                ->url(
                    route(
                        'PRINT.INVOICE-SALE',
                        ['sale' => $this->record]
                    )
                )
                ->openUrlInNewTab(),
        ];
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make('Detalles de Venta')
                    ->schema([
                        Grid::make()
                            ->schema([
                                Grid::make()
                                    ->schema([
                                        TextEntry::make('customer.name')
                                            ->label('Cliente'),
                                        TextEntry::make('user.name')
                                            ->label('Usuario'),
                                        TextEntry::make('invoice_number')
                                            ->label('Factura'),
                                    ])
                                    ->columns([
                                        'default' => 1,
                                        'sm' => 2,
                                        'md' => 3,
                                        'lg' => 3,
                                    ]),
                                Card::make()
                                    ->schema([
                                        TextEntry::make('notes')
                                            ->label('Nota')
                                            ->columnSpanFull(),
                                    ])
                                    ->columns(1),
                            ]),
                        Group::make()
                            ->columnSpanFull()
                            ->schema([
                                Group::make()
                                    ->schema([
                                        TableRepeatableEntry::make('saleDetails')
                                            ->label('')
                                            ->schema([
                                                TextEntry::make('product.name')
                                                    ->label('Descripción'),
                                                TextEntry::make('quantity')
                                                    ->label('Cantidad'),
                                                TextEntry::make('unit_price')
                                                    ->label('Pre. Unitario')
                                                    ->money(),
                                                TextEntry::make('total_price')
                                                    ->label('Total')
                                                    ->money(),
                                            ])
                                            ->striped()
                                            ->columnSpan('full'),
                                    ])
                                    ->columnSpan([
                                        'default' => 'full',
                                        'md' => 8,
                                        'lg' => 9,
                                    ]),

                                Grid::make()
                                    ->schema([
                                        Card::make('Resumen')
                                            ->schema([
                                                TextEntry::make('subtotal')
                                                    ->label(__('Sub Total'))
                                                    ->money('usd', true),
                                                TextEntry::make('tax')
                                                    ->label(__('IGV'))
                                                    ->money('usd', true),
                                                TextEntry::make('total')
                                                    ->label(__('Total'))
                                                    ->money('usd', true)
                                                    ->weight('bold'),
                                            ]),
                                    ])
                                    ->columnSpan([
                                        'default' => 'full',
                                        'md' => 4,
                                        'lg' => 3,
                                    ]),
                            ])
                            ->columns(12),
                    ]),
            ]);
    }
}
