<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\CashMovement;
use App\Models\CashRegister;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\Auth;
use App\Enum\CashMovement\MovementType;
use Filament\Notifications\Notification;
use Filament\Tables\Actions\ActionGroup;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\CashRegisterResource\Pages;
use App\Filament\Resources\CashRegisterResource\RelationManagers;

class CashRegisterResource extends Resource
{
    protected static ?string $model = CashRegister::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

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
                        Forms\Components\DatePicker::make('open_date')
                            ->required()
                            ->default(now())
                            ->disabled()
                            ->dehydrated(true),
                        Forms\Components\TextInput::make('initial_amount')
                            ->required()
                            ->numeric()
                            ->minValue(0)
                            ->step(0.01),
                    ])
                    ->columns(3),
                Forms\Components\Textarea::make('notes')
                    ->columnSpanFull(),
                Forms\Components\Grid::make()
                    ->schema([
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
                    ->sortable()
                    ->badge()
                    ->color('info'),
                Tables\Columns\TextColumn::make('close_date')
                    ->date()
                    ->sortable()
                    ->badge()
                    ->color('primary'),
                Tables\Columns\TextColumn::make('initial_amount')
                    ->searchable()
                    ->numeric()
                    ->sortable()
                    ->money()
                    ->badge()
                    ->color('success'),
                Tables\Columns\TextColumn::make('final_amount')
                    ->searchable()
                    ->money()
                    ->badge()->color('danger'),
                Tables\Columns\IconColumn::make('status')
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                ActionGroup::make([
                    Tables\actions\Action::make('close')
                        ->requiresConfirmation()
                        ->color('danger')
                        ->icon('fas-lock')
                        ->visible(fn($record) => $record->status)
                        ->action(function (CashRegister $record) {
                            $totalOutput = $record->cashMovements()
                                ->where('type', 'Salida')
                                ->sum('amount');
                            $totalInput = $record->cashMovements()
                                ->where('type', 'Entrada')
                                ->sum('amount');
                            $mountfinal = $record->initial_amount + $totalOutput - $totalInput;
                            $record->update([
                                'final_amount' => $mountfinal,
                                'status' => false,
                                'close_date' => now(),
                            ]);
                        }),
                    Tables\Actions\Action::make('Movement')
                        ->color('success')
                        ->icon('heroicon-o-inbox-arrow-down')
                        ->visible(fn($record) => $record->status)
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

                            Notification::make()
                                ->success()
                                ->title('Movimiento de Caja')
                                ->body(
                                    'Se ha registrado un movimiento de ' .
                                        ($data['type'] === 'Entrada' ? 'Entrada' : 'Salida') .
                                        ' por $' . number_format((float)$data['amount'], 2)
                                )
                                ->send();
                        }),
                    Tables\Actions\ViewAction::make()
                        ->color('info'),
                    Tables\Actions\EditAction::make()
                        ->color('warning'),
                    Tables\Actions\DeleteAction::make(),
                    Tables\Actions\ForceDeleteAction::make(),
                    Tables\Actions\RestoreAction::make(),
                ])
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
        return [
            //
        ];
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
