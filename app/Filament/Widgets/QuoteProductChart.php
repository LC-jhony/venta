<?php

namespace App\Filament\Widgets;

use App\Models\Quote;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class QuoteProductChart extends BaseWidget
{
    protected static ?int $sort = 4;

    protected int|string|array $columnSpan = 'full';

    protected static ?string $heading = 'Cotizaciones';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Quote::query()
                    ->latest()
                    ->with(['user', 'quoteProducts.product'])
            )
            ->columns([
                Tables\Columns\TextColumn::make('number_quote')
                    ->label('Número de cotización')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Usuario')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('valid_date')
                    ->label('Fecha de vencimiento')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('total')
                    ->label('Total')
                    ->money()
                    ->sortable(),
                Tables\Columns\IconColumn::make('status')
                    ->label('Estado')
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Creado')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->paginated([5, 10, 25, 50])
            ->defaultPaginationPageOption(5);
    }
}
