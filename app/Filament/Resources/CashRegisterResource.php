<?php

namespace App\Filament\Resources;

use App\Enum\CashMovement\MovementType;
use App\Filament\Resources\CashRegisterResource\Pages;
use App\Filament\Resources\CashRegisterResource\RelationManagers\CashMovementsEntradaRelationManager;
use App\Filament\Resources\CashRegisterResource\RelationManagers\CashMovementsRelationManager;
use App\Models\CashMovement;
use App\Models\CashRegister;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class CashRegisterResource extends Resource
{
    protected static ?string $model = CashRegister::class;

    protected static ?string $navigationIcon = 'fas-cash-register';

    protected static ?string $navigationGroup = 'Parchuse / Sale';

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
                        Forms\Components\DatePicker::make('open_date')
                            ->required()
                            ->default(now())
                            ->disabled()
                            ->dehydrated(true),
                        // Forms\Components\TextInput::make('final_amount')
                        //     ->numeric()
                        //     ->numeric()
                        //     ->minValue(0)
                        //     ->step(0.01)
                        //     ->disabled()
                        //     ->dehydrated(),
                    ])
                    ->columns(3),
                Forms\Components\Textarea::make('notes')
                    ->columnSpanFull(),
                Forms\Components\Grid::make()
                    ->schema([

                        // Forms\Components\DatePicker::make('close_date')
                        //     ->after('open_date'),
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
                Tables\Columns\TextColumn::make('open_date')
                    ->date()
                    ->badge()
                    ->color('info'),
                Tables\Columns\TextColumn::make('initial_amount')
                    ->numeric()
                    ->sortable()
                    ->money()
                    ->badge()
                    ->color('success'),

                // Tables\Columns\TextColumn::make('cashMovements.amount')
                //     ->badge()
                //     ->formatStateUsing(function ($state, $record) {
                //         $output = $record->cashMovements()
                //             ->where('type', 'Salida')
                //             ->pluck('amount');
                //         return $output;
                //     }),
                Tables\Columns\TextColumn::make('final_amount')
                    ->money()
                    ->badge()->color('danger'),
                Tables\Columns\TextColumn::make('close_date')
                    ->date()
                    ->sortable()
                    ->badge()
                    ->color('primary'),
                Tables\Columns\IconColumn::make('status')
                    ->boolean()
            ])
            ->filters([
                //
            ])
            ->actions([
                //Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('close')
                    ->requiresConfirmation()
                    ->color('danger')
                    ->icon('fas-lock')
                    ->visible(fn($record) => $record->status)
                    ->action(
                        function (CashRegister $record) {
                            $totalOutput = $record->cashMovements()
                                ->where('type', 'Salida')
                                ->sum('amount');
                            $totalInput = $record->cashMovements()
                                ->where('type', 'Entrada')
                                ->sum('amount');
                            $mountfinal = $record->initial_amount + $totalOutput - $totalInput;
                            dump($record->initial_amount, $totalOutput, $totalInput, $mountfinal);
                            $record->update([
                                'final_amount' => $mountfinal,
                                'status' => false,
                                'close_date' => now(),
                            ]);
                            Notification::make()
                                ->title('Cierre de caja')
                                ->body('La caja ha sido cerrada con éxito')
                                ->success();
                        }
                    ),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\Action::make('Movement')
                    ->icon('heroicon-o-inbox-arrow-down')
                    ->fillForm(fn(CashRegister $record): array => [
                        'user_id' => $record->user_id,
                        'cash_register_id' => $record->id,
                    ])
                    ->form([
                        Forms\Components\Hidden::make('cash_register_id')
                            ->required(),
                        Forms\Components\Grid::make()
                            ->schema([
                                Forms\Components\Select::make('type')
                                    ->options(MovementType::class)
                                    ->required()
                                    ->native(false),
                                Forms\Components\TextInput::make('amount')
                                    ->required(),
                            ])->columns(2),
                        Forms\Components\Textarea::make('description'),
                    ])
                    ->action(function (array $data, CashRegister $record): void {
                        CashMovement::create($data);
                    })
                    ->successNotification(
                        Notification::make()
                            ->success()
                            ->title('Cash Movement'),
                    ),
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
