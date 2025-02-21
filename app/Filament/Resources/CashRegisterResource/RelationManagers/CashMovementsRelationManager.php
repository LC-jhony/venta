<?php

namespace App\Filament\Resources\CashRegisterResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Enum\CashMovement\MovementType;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\RelationManagers\RelationManager;

class CashMovementsRelationManager extends RelationManager
{
    protected static string $relationship = 'cashMovements';
    protected function getTableQuery(): Builder
    {
        $query = parent::getTableQuery() ?? $this->getRelationship()->getQuery();

        return $query->where('type', 'Entrada');
    }
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('cash_register_id')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('cash_register_id')
            ->columns([
                Tables\Columns\TextColumn::make('type')
                    ->formatStateUsing(fn() => 'Entrada'),
                // ->color(fn(string $state): string => match ($state) {
                //     'Entrada' => 'success',
                //     'Salida' => 'danger',
                // }),
                Tables\Columns\TextColumn::make('amount'),
                Tables\Columns\TextColumn::make('description'),
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
