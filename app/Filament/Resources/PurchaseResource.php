<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Quote;
use App\Models\Product;
use Filament\Forms\Get;
use Filament\Forms\Set;
use App\Models\Purchase;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Awcodes\TableRepeater\Header;
use Illuminate\Support\Facades\Auth;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\PurchaseResource\Pages;
use Awcodes\TableRepeater\Components\TableRepeater;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PurchaseResource extends Resource
{
    protected static ?string $model = Purchase::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';

    protected static ?string $navigationGroup = 'Parchuse / Sale';
    protected static ?string $modelLabel = 'Compras';
    //protected static ?string $recordTitleAttribute = 'purchase_number'; // para que se pueda buscar de manera global

    protected static ?string $activeNavigationIcon = 'heroicon-o-check-badge'; // cambiar el icono de la seccion activa

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Card::make()
                    ->schema([
                        Forms\Components\Select::make('user_id')
                            ->label('Usuario')
                            ->relationship('user', 'name')
                            ->default(Auth::id() ?? 1)
                            ->disabled()
                            ->dehydrated()
                            ->required(),
                        Forms\Components\Select::make('supplier_id')
                            ->label('Proveedor')
                            // ->relationship('supplier', 'name')

                            ->options(function (Get $get) {
                                $quoteId = $get('quote_id');
                                if (!$quoteId) {
                                    return [];
                                }

                                $quote = Quote::with('suppliers')->find($quoteId);
                                if (!$quote) {
                                    return [];
                                }

                                return $quote->suppliers->pluck('name', 'id');
                            })
                            ->required()
                            ->searchable()
                            ->preload()
                            ->native(false)
                            ->live()
                            ->disabled(fn(Get $get) => !$get('quote_id')),
                        Forms\Components\Select::make('quote_id')
                            ->label('Cotización')
                            ->options(function () {
                                return Quote::where('status', true)
                                    ->pluck('number_quote', 'id');
                            })
                            ->searchable()
                            ->preload()
                            ->live()
                            ->afterStateUpdated(function ($state, Set $set, Get $get) {
                                if (!$state) return;

                                $quote = Quote::with('quoteProducts.product')->find($state);
                                if (!$quote) return;

                                // Eliminar esta sección que auto-selecciona el proveedor
                                // if ($quote->suppliers->first()) {
                                //     $set('supplier_id', $quote->suppliers->first()->id);
                                // }

                                // Preparar detalles de compra desde la cotización
                                $details = $quote->quoteProducts->map(function ($quoteProduct) {
                                    return [
                                        'product_id' => $quoteProduct->product_id,
                                        'quantity' => $quoteProduct->quantity,
                                        'unit_cost' => $quoteProduct->price_unit,
                                    ];
                                })->toArray();

                                $set('detailparchuse', $details);
                                self::calculatePurchaseTotal($details, $set);
                            }),


                    ])->columns(3),
                Forms\Components\Group::make()
                    ->columnSpanFull()
                    ->schema([
                        Forms\Components\Group::make()
                            ->schema([
                                TableRepeater::make('detailparchuse')
                                    ->relationship()
                                    ->label('')
                                    ->live()
                                    ->afterStateUpdated(function (Get $get, Set $set) {
                                        $details = $get('detailparchuse');
                                        if ($details) {
                                            self::calculatePurchaseTotal($details, $set);
                                        }
                                    })
                                    ->headers([
                                        Header::make('description'),
                                        Header::make('Cantidad')->width('120px'),
                                        Header::make('Pre. Unitario')->width('120px'),
                                    ])
                                    ->schema([
                                        Forms\Components\Select::make('product_id')

                                            ->relationship('product', 'name')
                                            ->preload()
                                            ->searchable()
                                            ->live()
                                            ->dehydrated()
                                            ->afterStateUpdated(function (callable $set, $state) {
                                                $product = Product::find($state);
                                                if ($product) {
                                                    $set('unit_cost', $product->purchase_price);
                                                }
                                            })
                                            ->required(),
                                        Forms\Components\TextInput::make('quantity')

                                            ->numeric()
                                            ->default(1)
                                            ->live()
                                            ->dehydrated()
                                            ->required(),

                                        Forms\Components\TextInput::make('unit_cost')

                                            ->live()
                                            ->dehydrated()
                                            ->readOnly()
                                            ->required(),

                                    ])
                                    ->emptyLabel('Seleccione un producto')
                                    ->defaultItems(0)
                                    ->reorderable()
                                    ->columnSpan('full'),
                            ])->columnSpan(9),
                        Forms\Components\Card::make()
                            ->schema([
                                Forms\Components\TextInput::make('total')
                                    ->label('Total')
                                    ->prefix('S/.')
                                    ->disabled()
                                    ->required()
                                    ->dehydrated(true),
                                Forms\Components\TextInput::make('purchase_number')
                                    ->label('N° Compra')
                                    ->required()
                                    ->dehydrated()
                                    ->default('ORDCMP-' . now()->format('Ymd') . '-' . rand(1000, 99999999))
                                    ->maxLength(255),
                                Forms\Components\Select::make('status')
                                    ->label('estado')
                                    ->options([
                                        '1' => 'Aceptado',
                                        '0' => 'Rechazado',
                                    ])
                                    ->required()
                                    ->native(false),

                            ])->columnSpan(3),
                    ])
                    ->columns(12),
            ]);
    }

    private static function calculatePurchaseTotal($detailparchuse, callable $set)
    {
        $total = 0;
        foreach ($detailparchuse as $detail) {

            // $total += $detail['unit_cost'];
            $total += $detail['quantity'] * $detail['unit_cost'];
        }
        $set('total',  number_format($total, 2, '.', ''));
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Usuario')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('supplier.name')
                    ->label('Proveedor')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('purchase_number')
                    ->label('Numero')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('total')
                    ->label('Total')
                    ->money('S/.'),
                Tables\Columns\IconColumn::make('status')
                    ->label('Estado')
                    ->boolean()
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
                SelectFilter::make('status')
                    ->label('Estado')
                    ->options([
                        '1' => 'Aceptado',
                        '0' => 'Rechazado',
                    ])
                    ->native(false),
                SelectFilter::make('user_id')
                    ->label('Usuario')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload()
                    ->native(false),
                SelectFilter::make('supplier_id')
                    ->label('Proveedor')
                    ->relationship('supplier', 'name')
                    ->searchable()
                    ->preload()
                    ->native(false)
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
            'index' => Pages\ListPurchases::route('/'),
            'create' => Pages\CreatePurchase::route('/create'),
            'view' => Pages\ViewPurchase::route('/{record}'),
            'edit' => Pages\EditPurchase::route('/{record}/edit'),
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
