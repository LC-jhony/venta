<?php

namespace App\Filament\Resources\CashRegisterResource\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class CashMovementsEntradaRelationManager extends RelationManager
{
    protected static string $relationship = 'cashMovements';

    protected static ?string $title = 'Entry ';

    protected static ?string $icon = 'heroicon-o-archive-box-arrow-down';

    protected function getTableQuery(): Builder
    {
        $query = parent::getTableQuery() ?? $this->getRelationship()->getQuery();

        return $query->where('type', 'Entrada');
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('cash_register_id')
            ->columns([
                Tables\Columns\TextColumn::make('type'),
                Tables\Columns\TextColumn::make('description')
                    ->placeholder('No description'),
                Tables\Columns\TextColumn::make('amount')
                    ->label('Entry')
                    ->money()
                    ->summarize(Sum::make()),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
