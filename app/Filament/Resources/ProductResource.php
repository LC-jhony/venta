<?php

namespace App\Filament\Resources;

use App\Enum\TypeMeasure;
use App\Filament\Resources\ProductResource\Pages;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-cube';

    protected static ?string $navigationGroup = 'Siatema POS';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Card::make()
                    ->schema([
                        Forms\Components\Card::make()
                            ->schema([
                                Forms\Components\FileUpload::make('image')
                                    ->disk('public')
                                    ->directory('products')
                                    ->imageResizeMode('contain')
                                    ->extraAttributes([
                                        'class' => 'aspect-[3/2] w-[9.375rem] max-w-full',
                                    ]),
                            ])->columnSpan(1),

                        Forms\Components\Card::make()
                            ->schema([
                                Forms\Components\Grid::make()
                                    ->schema([
                                        Forms\Components\TextInput::make('bar_code')
                                            // ->label('Codigo barra')
                                            ->required(),
                                        Forms\Components\TextInput::make('name')
                                            //  ->label('Nombre')
                                            ->required(),
                                        Forms\Components\TextInput::make('purchase_price')
                                            // ->label('Precio de compra')
                                            ->required()
                                            ->live()
                                            ->afterStateUpdated(function ($state, callable $set) {
                                                if ($state) {
                                                    $salesPrice = $state / (1 - 0.35);
                                                    $set('sales_price', number_format($salesPrice, 2, '.', ''));
                                                }
                                            }),
                                        Forms\Components\TextInput::make('sales_price')
                                            // ->label('Precio de venta')
                                            ->required()
                                            ->live()
                                            ->disabled()
                                            ->dehydrated(true),
                                    ])->columns(2),
                            ])->columnSpan(3),
                    ])->columns(4),
                Forms\Components\Card::make()
                    ->schema([
                        Forms\Components\TextInput::make('stock')
                            // ->label('Stock')
                            ->required(),
                        Forms\Components\TextInput::make('stock_minimum')
                            // ->label('Stock minimo')
                            ->required(),
                        Forms\Components\Select::make('unit_measure')
                            // ->label('Unidad de medida')
                            ->options(TypeMeasure::class)
                            ->searchable()
                            ->preload()
                            ->required()
                            ->native(false),
                        Forms\Components\Select::make('category_id')
                            // ->label('Categoria')
                            ->relationship('category', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),
                        Forms\Components\Select::make('status')
                            // ->label('Estado')
                            ->required()
                            ->options([
                                true => 'Activo',
                                false => 'Inactivo',
                            ])
                            ->native(false),
                        Forms\Components\DatePicker::make('expiration')
                            //  ->label('Fecha de vencimiento')
                            ->required()
                            ->native(false),
                    ])->columns(2),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->striped()
            ->paginated([5, 10, 25, 50, 100, 'all'])
            ->defaultPaginationPageOption(5)
            ->columns([
                Tables\Columns\ImageColumn::make('image')
                    // ->label('Imagen')
                    ->disk('public')
                    ->circular()
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('bar_code')
                    // ->label('Codigo de barra')
                    ->searchable(),
                Tables\Columns\TextColumn::make('name')
                    // ->label('Nombre')
                    ->searchable(),
                Tables\Columns\TextColumn::make('purchase_price')
                    // ->label('Precio de compra')
                    ->money('usd', true),
                Tables\Columns\TextColumn::make('sales_price')
                    // ->label('Precio de venta')
                    ->money('usd', true),
                Tables\Columns\TextColumn::make('stock')
                    // ->label('Stock')
                    ->searchable(),
                Tables\Columns\TextColumn::make('stock_minimum')
                    // ->label('Stock minimo')
                    ->searchable()
                    ->badge()
                    ->color(
                        fn ($record): string => $record->stock <= $record->stock_minimum ? 'danger' : ($record->stock <= $record->stock_minimum * 2 ? 'warning' : 'success')
                    )
                    ->icon(
                        fn ($record) => $record->stock <= $record->stock_minimum ? 'heroicon-o-exclamation-triangle' : ($record->stock <= $record->stock_minimum * 2 ? 'heroicon-o-exclamation-circle' : 'heroicon-o-check-circle')
                    ),
                Tables\Columns\TextColumn::make('category.name')
                    // ->label('Categoria')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\IconColumn::make('status')
                    // ->label('Estado')
                    ->boolean()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('unit_measure')
                    // ->label('Unidad de medida')
                    ->badge()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('expiration')
                    // ->label('Fecha de vencimiento')
                    ->badge()
                    ->date()
                    ->color(
                        fn ($record): string => now()->diffInDays($record->expiration, false) <= 0 ? 'danger' : (now()->diffInDays($record->expiration, false) <= 30 ? 'warning' : 'success')
                    )
                    ->icon(
                        fn ($record) => now()->diffInDays($record->expiration, false) <= 0 ? 'heroicon-o-x-circle' : (now()->diffInDays($record->expiration, false) <= 30 ? 'heroicon-o-exclamation-circle' : 'heroicon-o-check-circle')
                    ),
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
                SelectFilter::make('category_id')
                    ->label('Categoria')
                    ->relationship('category', 'name')
                    ->searchable()
                    ->preload(),
                SelectFilter::make('status')
                    ->label('Estado')
                    ->options([
                        true => 'Activo',
                        false => 'Inactivo',
                    ])
                    ->native(false),
                SelectFilter::make('stock_minimum')
                    ->label('Estado de Stock')
                    ->options([
                        'danger' => 'Stock Crítico',
                        'warning' => 'Stock Bajo',
                        'success' => 'Stock Normal',
                    ])
                    ->query(function ($query, $data) {
                        if ($data['value']) {
                            return match ($data['value']) {
                                'danger' => $query->whereRaw('stock <= stock_minimum'),
                                'warning' => $query->whereRaw('stock > stock_minimum AND stock <= (stock_minimum * 2)'),
                                'success' => $query->whereRaw('stock > (stock_minimum * 2)'),
                                default => $query
                            };
                        }

                        return $query;
                    })
                    ->native(false),
                SelectFilter::make('expiration_status')
                    ->label('Estado de Vencimiento')
                    ->options([
                        'danger' => 'Vencido',
                        'warning' => 'Por vencer',
                        'success' => 'Vigente',
                    ])
                    ->query(function ($query, $data) {
                        if ($data['value']) {
                            return match ($data['value']) {
                                'danger' => $query->whereRaw('DATEDIFF(NOW(), expiration) >= 0'),
                                'warning' => $query->whereRaw('DATEDIFF(NOW(), expiration) < 0 AND DATEDIFF(NOW(), expiration) >= -30'),
                                'success' => $query->whereRaw('DATEDIFF(NOW(), expiration) < -30'),
                                default => $query
                            };
                        }

                        return $query;
                    })
                    ->native(false),
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
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'view' => Pages\ViewProduct::route('/{record}'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
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
