<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CashRegisterResource\Pages;
use App\Models\CashRegister;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class CashRegisterResource extends Resource
{
    protected static ?string $model = CashRegister::class;

    protected static ?string $navigationIcon = 'fas-cash-register';

    protected static ?string $navigationGroup = 'Compras / Ventas';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Grid::make()
                    ->schema([
                        Forms\Components\Select::make('user_id')
                            ->relationship('user', 'name')
                            ->default(Auth::id() ?? 1)
                            ->required()
                            ->disabled()
                            ->dehydrated(),
                        Forms\Components\TextInput::make('initial_amount')
                            ->required()
                            ->numeric()
                            ->minValue(0)
                            ->step(0.01),
                        Forms\Components\TextInput::make('final_amount')
                            ->numeric()
                            ->numeric()
                            ->minValue(0)
                            ->step(0.01)
                            ->disabled()
                            ->dehydrated(),
                    ])
                    ->columns(3),
                Forms\Components\Textarea::make('notes')
                    ->columnSpanFull(),
                Forms\Components\Grid::make()
                    ->schema([
                        Forms\Components\DatePicker::make('open_date')
                            ->required()
                            ->default(now())
                            ->disabled()
                            ->dehydrated(true),
                        Forms\Components\DatePicker::make('close_date')
                            ->after('open_date'),
                        Forms\Components\Toggle::make('status')
                            ->required()
                            ->default(true)
                            ->disabled(fn($record) => $record && ! $record->status),
                    ])->columns(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('initial_amount')
                    ->numeric()
                    ->sortable()
                    ->money()
                    ->badge()->color('success'),
                Tables\Columns\TextColumn::make('final_amount')
                    ->numeric()
                    ->sortable()
                    ->money(),
                Tables\Columns\TextColumn::make('sale_total')
                    ->numeric()
                    ->badge()
                    ->state(function (CashRegister $record): float {
                        return $record->totals()->sum('sale_total');
                    })
                    ->money()
                    ->sortable(),
                Tables\Columns\TextColumn::make('purchase_total')
                    ->numeric()
                    ->badge()->color('danger')
                    ->sortable()
                    ->state(function (CashRegister $record): float {
                        return $record->totals()->sum('purchase_total');
                    })
                    ->money(),
                Tables\Columns\TextColumn::make('open_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('close_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\IconColumn::make('status')
                    ->boolean()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('close')
                    ->requiresConfirmation()
                    ->color('danger')
                    ->icon('fas-lock')
                    ->visible(fn($record) => $record->status)
                    ->action(function (CashRegister $record) {
                        // Refrescamos el registro con las relaciones necesarias
                        $record->load('totals');

                        // Calculamos el balance actual
                        $currentBalance = $record->initial_amount +
                            $record->totals()->sum('sale_total') -
                            $record->totals()->sum('purchase_total');

                        $record->update([
                            'status' => false,
                            'close_date' => now(),
                            'final_amount' => $currentBalance,
                        ]);
                        Notification::make()
                            ->title('Cierre de caja')
                            ->body('La caja ha sido cerrada con éxito')
                            ->success();
                    }),


            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCashRegisters::route('/'),
            'create' => Pages\CreateCashRegister::route('/create'),
            'view' => Pages\ViewCashRegister::route('/{record}'),
            'edit' => Pages\EditCashRegister::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
