<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\CashRegister;
use Filament\Resources\Resource;
use Filament\Tables\Actions\Action;
use Illuminate\Support\Facades\Auth;
use App\Enum\CashMovement\MovementType;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\CashRegisterResource\Pages;
use App\Filament\Resources\CashRegisterResource\RelationManagers;
use App\Filament\Resources\CashRegisterResource\RelationManagers\CashMovementsRelationManager;
use App\Filament\Resources\CashRegisterResource\RelationManagers\CashMovementsSalidaRelationManager;
use App\Filament\Resources\CashRegisterResource\RelationManagers\CashMovementsEntradaRelationManager;

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
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('close')
                    ->requiresConfirmation()
                    ->color('danger')
                    ->icon('fas-lock')
                    ->visible(fn($record) => $record->status),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\Action::make('Movement')
                    ->icon('heroicon-o-inbox-arrow-down')
                    ->form([
                        Forms\Components\Select::make('amount')
                            ->options(MovementType::class)
                            ->required()
                            ->native(false),
                        Forms\Components\TextInput::make('sale_total'),
                        Forms\Components\TextInput::make('purchase_total')
                    ]),


            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            CashMovementsRelationManager::class,
            CashMovementsEntradaRelationManager::class,

        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCashRegisters::route('/'),
            'create' => Pages\CreateCashRegister::route('/create'),
            'edit' => Pages\EditCashRegister::route('/{record}/edit'),
        ];
    }
}
