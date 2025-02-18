<?php

namespace App\Filament\Resources\CashRegisterResource\RelationManagers;

use App\Enum\CashMovement\MovementType;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CashMovementsRelationManager extends RelationManager
{
    protected static string $relationship = 'cashMovements';
    protected static ?string $title = 'Output';
    protected static ?string $icon = 'heroicon-o-archive-box';

    protected function getTableQuery(): Builder
    {
        $query = parent::getTableQuery() ?? $this->getRelationship()->getQuery();
        return $query->where('type', 'Salida');
    }
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('type')
                    ->options(MovementType::class)
                    ->required()
                    ->native(false),
                Forms\Components\TextInput::make('amount')
                    ->required(),
                Forms\Components\Textarea::make('description')
            ]);
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
                    ->label('Output')
                    ->money()
                    ->summarize(Sum::make())



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
