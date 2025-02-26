<?php

namespace App\Filament\Resources\QuoteResource\Pages;

use Filament\Actions;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Pages\Actions\Action;
use Illuminate\Database\Eloquent\Model;
use Filament\Infolists\Components\Split;
use Filament\Resources\Pages\ViewRecord;
use App\Filament\Resources\QuoteResource;
use Icetalker\FilamentTableRepeatableEntry\Infolists\Components\TableRepeatableEntry;

class ViewQuote extends ViewRecord
{
    protected static string $resource = QuoteResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
            Action::make('print')
                ->icon('fluentui-print-48-o')
                ->outlined()
                ->url(fn($record): string => route('QUOTE-INVOICE', $record->id))
              
        ];
    }
    public function getRecord(): Model
    {
        $record = parent::getRecord();

        // Asegurarse de que la relación suppliers esté cargada
        if (!$record->relationLoaded('suppliers')) {
            $record->load('suppliers');
        }

        return $record;
    }
    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([

                Infolists\Components\Section::make('Cotización')
                    ->schema([
                        Infolists\Components\Grid::make()
                            ->schema([
                                Infolists\Components\TextEntry::make('user.name')
                                    ->label('Usuario'),
                                Infolists\Components\TextEntry::make('number_quote')
                                    ->label('Número de cotización'),
                                Infolists\Components\TextEntry::make('valid_date')
                                    ->label('Fecha de vencimiento'),
                            ])
                            ->columns([
                                'default' => 1,
                                'sm' => 2,
                                'md' => 3,
                                'lg' => 3,
                            ]),
                    ]),

                Infolists\Components\Group::make()
                    ->columnSpanFull()
                    ->schema([
                        Infolists\Components\Group::make()
                            ->schema([
                                TableRepeatableEntry::make('quoteProducts')
                                    ->label('')
                                    ->schema([
                                        Infolists\Components\TextEntry::make('product.name')
                                            ->label('Descripción'),
                                        Infolists\Components\TextEntry::make('quantity')
                                            ->label('Cantidad'),
                                        Infolists\Components\TextEntry::make('price_unit')
                                            ->label('Precio Unitario'),
                                        Infolists\Components\TextEntry::make('total_price')
                                            ->label('Pre. Total'),
                                    ])
                                    ->striped()
                                    ->columnSpan('full'),
                            ])
                            ->columnSpan([
                                'default' => 'full',
                                'md' => 8,
                                'lg' => 9,
                            ]),

                        Infolists\Components\Card::make()
                            ->schema([
                                Infolists\Components\Card::make('Total | Estado')
                                    ->schema([
                                        Infolists\Components\TextEntry::make('status')
                                            ->label('Estado'),
                                        Infolists\Components\TextEntry::make('total')
                                            ->label('Total')
                                            ->money('usd', true),
                                    ])
                                    ->columns(1),

                                Infolists\Components\Grid::make()
                                    ->schema([
                                        Infolists\Components\RepeatableEntry::make('quoteSuppliers')
                                            ->label('Proveedores')
                                            ->schema([
                                                Infolists\Components\TextEntry::make('supplier.name')
                                                    ->label('Nombre'),
                                            ]),
                                    ])
                                    ->columns(1),
                            ])
                            ->columnSpan([
                                'default' => 'full',
                                'md' => 4,
                                'lg' => 3,
                            ]),
                    ])
                    ->columns(12),

                Infolists\Components\TextEntry::make('notes')
                    ->label('Nota')
                    ->columnSpanFull(),
            ]);
    }
}
