<?php

namespace App\Filament\Resources\CashRegisterResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class OutputsRelationManager extends RelationManager
{
    protected static string $relationship = 'cashMovements';

    protected static ?string $title = 'Ventas';

    protected function getTableQuery(): Builder
    {
        $query = parent::getTableQuery() ?? $this->getRelationship()->getQuery();

        return $query->where('type', 'Salida');
    }

    public function form(Form $form): Form
    {

        return $form
            ->schema([
                Forms\Components\TextInput::make('type')
                    ->default('Salida')
                    ->disabled()
                    ->required()
                    ->dehydrated(),
                Forms\Components\TextInput::make('amount')
                    ->required(),
                Forms\Components\Textarea::make('description')
                    ->columnSpanFull(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('description')
            ->columns([
                Tables\Columns\TextColumn::make('type'),
                Tables\Columns\TextColumn::make('amount')
                    ->money()
                    ->summarize(Sum::make()),
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
