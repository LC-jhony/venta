<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SuppliersResource\Pages;
use App\Models\Supplier;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SuppliersResource extends Resource
{
    protected static ?string $model = Supplier::class;

    protected static ?string $navigationIcon = 'gmdi-engineering-tt';

    protected static ?string $navigationGroup = 'Sistem POS';
    protected static ?string $modelLabel = 'Proveedores';

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Registro de Proveedores')
                    ->schema([
                        Forms\Components\Grid::make()
                            ->schema([
                                Forms\Components\TextInput::make('name')
                                    ->label('Nombre')
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('email')
                                    ->label('Correo Electrónico')
                                    ->email()
                                    ->required()
                                    ->maxLength(255),
                            ])
                            ->columns([
                                'default' => 1,
                                'sm' => 1,
                                'md' => 2,
                                'lg' => 2,
                                'xl' => 2,
                            ]),
                        Forms\Components\Grid::make()
                            ->schema([
                                Forms\Components\TextInput::make('phone')
                                    ->label('Teléfono | Celular')
                                    ->tel()
                                    ->required()
                                    ->regex('/^\+?[0-9]{1,4}?[-.\s]?\(?\d{1,3}?\)?[-.\s]?\d{1,4}[-.\s]?\d{1,4}[-.\s]?\d{1,9}$/'),
                                Forms\Components\TextInput::make('address')
                                    ->label('Dirección')
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\Select::make('status')
                                    ->label('Estado')
                                    ->options([
                                        '1' => 'Activo',
                                        '0' => 'Inactivo',
                                    ])
                                    ->required()
                                    ->native(false),
                            ])
                            ->columns([
                                'default' => 1,
                                'sm' => 1,
                                'md' => 3,
                                'lg' => 3,
                                'xl' => 3,
                            ]),
                        Forms\Components\Textarea::make('description')
                            ->label('Descripción')
                            ->columnSpanFull(),
                        Forms\Components\Section::make('Datos de Ubicación')
                            ->schema([
                                Forms\Components\TextInput::make('country')
                                    ->label('País')
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('city')
                                    ->label('Ciudad')
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('state')
                                    ->label('Estado/Provincia')
                                    ->maxLength(255),
                            ])
                            ->columns([
                                'default' => 1,
                                'sm' => 1,
                                'md' => 3,
                                'lg' => 3,
                                'xl' => 3,
                            ])
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
        ->striped()
        ->paginated([5, 10, 25, 50, 100, 'all'])
        ->defaultPaginationPageOption(5)
            ->columns([
                Tables\Columns\TextColumn::make('name')
                ->label('Nombre')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                ->label('Correo')
                    ->searchable(),
                Tables\Columns\TextColumn::make('phone')
                ->label('Telefono')
                    ->searchable(),
                Tables\Columns\TextColumn::make('address')
                ->label('Dirección')
                    ->searchable(),
                Tables\Columns\IconColumn::make('status')
                ->label('Estado')
                    ->boolean(),
                Tables\Columns\TextColumn::make('country')
                ->label('Pais')
                    ->searchable(),
                Tables\Columns\TextColumn::make('city')
                ->label('Ciudad')
                    ->searchable(),
                Tables\Columns\TextColumn::make('state')
                ->label('Estado')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                ->label('Creado')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                ->label('Actualizado')
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
                Tables\Filters\TrashedFilter::make(),
                Tables\Filters\SelectFilter::make('status')
                    ->label('Estado')
                    ->options([
                        '1' => 'Activo',
                        '0' => 'Inactivo',
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\ForceDeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
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
            'index' => Pages\ListSuppliers::route('/'),
            'create' => Pages\CreateSuppliers::route('/create'),
            'view' => Pages\ViewSuppliers::route('/{record}'),
            'edit' => Pages\EditSuppliers::route('/{record}/edit'),
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
