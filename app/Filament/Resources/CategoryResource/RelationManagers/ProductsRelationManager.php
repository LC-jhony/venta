<?php

namespace App\Filament\Resources\CategoryResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class ProductsRelationManager extends RelationManager
{
    protected static string $relationship = 'products';

    protected static ?string $title = 'Productos';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->striped()
            ->paginated([5, 10, 25, 50, 100, 'all'])
            ->defaultPaginationPageOption(5)
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\ImageColumn::make('image')
                    ->label('Imagen')
                    ->disk('public')
                    ->circular()
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('bar_code')
                    ->label('Cod. barra')
                    ->searchable(),
                Tables\Columns\TextColumn::make('name')
                    ->label('Nombre')
                    ->searchable(),
                Tables\Columns\TextColumn::make('purchase_price')
                    ->label('Pre. compra')
                    ->money('usd', true),
                Tables\Columns\TextColumn::make('sales_price')
                    ->label('Pre. venta')
                    ->money('usd', true),
                Tables\Columns\TextColumn::make('stock')
                    ->label('Stock')
                    ->searchable(),
                Tables\Columns\TextColumn::make('stock_minimum')
                    ->label('Stock minimo')
                    ->searchable()
                    ->badge()
                    ->color(
                        fn ($record): string => $record->stock <= $record->stock_minimum ? 'danger' : ($record->stock <= $record->stock_minimum * 2 ? 'warning' : 'success')
                    )
                    ->icon(
                        fn ($record) => $record->stock <= $record->stock_minimum ? 'heroicon-o-exclamation-triangle' : ($record->stock <= $record->stock_minimum * 2 ? 'heroicon-o-exclamation-circle' : 'heroicon-o-check-circle')
                    ),
                Tables\Columns\TextColumn::make('category.name')
                    ->label('Categoria')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\IconColumn::make('status')
                    ->label('Estado')
                    ->boolean()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('unit_measure')
                    ->label('UND')
                    ->badge()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('expiration')
                    ->label('Ven. fecha')
                    ->badge()
                    ->date()
                    ->color(
                        fn ($record): string => now()->diffInDays($record->expiration, false) <= 0 ? 'danger' : (now()->diffInDays($record->expiration, false) <= 30 ? 'warning' : 'success')
                    )
                    ->icon(
                        fn ($record) => now()->diffInDays($record->expiration, false) <= 0 ? 'heroicon-o-x-circle' : (now()->diffInDays($record->expiration, false) <= 30 ? 'heroicon-o-exclamation-circle' : 'heroicon-o-check-circle')
                    ),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Creado')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Modificado')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->label('Eliminado')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
                Tables\Actions\ForceDeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
