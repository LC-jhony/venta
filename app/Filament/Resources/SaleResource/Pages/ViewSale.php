<?php

namespace App\Filament\Resources\SaleResource\Pages;

use Filament\Actions;
use Filament\Infolists\Infolist;
use Awcodes\TableRepeater\Header;
use Filament\Infolists\Components\Card;
use Filament\Infolists\Components\Grid;
use App\Filament\Resources\SaleResource;
use Filament\Infolists\Components\Group;
use Filament\Resources\Pages\ViewRecord;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Pages\Actions\Action;
use Filament\Infolists\Components\RepeatableEntry;
use Awcodes\TableRepeater\Components\TableRepeater;
use PrintFilament\Print\Infolists\Components\PrintComponent;
use Icetalker\FilamentTableRepeatableEntry\Infolists\Components\TableRepeatableEntry;

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
        ];
    }
    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make()
                    ->schema([
                        Section::make()
                            ->schema([
                                Grid::make()
                                    ->schema([
                                        TextEntry::make('customer.name')
                                            ->label(__('Customer')),
                                        TextEntry::make('user.name')
                                            ->label(__('User')),
                                        TextEntry::make('invoice_number')
                                            ->label(__('Invoice')),
                                        // TextEntry::make('created_at'),
                                    ])
                                    ->columns(3),
                                Card::make()
                                    ->schema([
                                        TextEntry::make('payment_method')
                                            ->label(__('Method')),
                                        TextEntry::make('payment_status')
                                            ->label(__('Status')),
                                        TextEntry::make('sale_status')
                                            ->label(__('Sale Status'))
                                    ])
                                    ->columns(3)
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
                                                    ->label(__('Description')),
                                                TextEntry::make('quantity')
                                                    ->label(__('Quantity')),
                                                TextEntry::make('unit_price')
                                                    ->label(__('Price Unit')),
                                                TextEntry::make('total_price')
                                                    ->label(__('Total Price')),
                                            ])
                                            ->striped()
                                            ->columnSpan('full')
                                    ])->columnSpan(10),

                                Grid::make()
                                    ->schema([
                                        Card::make()
                                            ->schema([
                                                TextEntry::make('subtotal')
                                                    ->label(__('Sub Total')),
                                                TextEntry::make('tax')
                                                    ->label(__('IGV')),
                                                TextEntry::make('total')
                                                    ->label(__('Total')),
                                            ])
                                    ])->columnSpan(2),
                            ])
                            ->columns(12),
                        Card::make()
                            ->schema([
                                TextEntry::make('notes')
                                    ->label(__('Notes'))
                                    ->columnSpanFull()
                            ])


                    ])
            ]);
    }
}
