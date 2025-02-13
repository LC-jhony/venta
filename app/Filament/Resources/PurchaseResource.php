<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PurchaseResource\Pages;
use App\Models\Product;
use App\Models\Purchase;
use Awcodes\TableRepeater\Components\TableRepeater;
use Awcodes\TableRepeater\Header;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class PurchaseResource extends Resource
{
    protected static ?string $model = Purchase::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';

    protected static ?string $navigationGroup = 'Compras / Ventas';

    protected static ?string $recordTitleAttribute = 'purchase_number'; // para que se pueda buscar de manera global

    protected static ?string $activeNavigationIcon = 'heroicon-o-check-badge'; // cambiar el icono de la seccion activa

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Card::make()
                    ->schema([
                        Forms\Components\Select::make('user_id')
                            ->relationship('user', 'name')
                            ->default(Auth::id() ?? 1)
                            ->disabled()
                            ->dehydrated()
                            ->required(),
                        Forms\Components\Select::make('supplier_id')
                            ->relationship('supplier', 'name')
                            ->required()
                            ->searchable()
                            ->preload()
                            ->native(false),

                    ])->columns(2),
                Forms\Components\Group::make()
                    ->columnSpanFull()
                    ->schema([
                        Forms\Components\Group::make()
                            ->schema([
                                TableRepeater::make('detailparchuse')
                                    ->relationship()
                                    ->label('')

                                    ->headers([
                                        Header::make('description'),
                                        Header::make('quantity')->width('120px'),
                                        Header::make('Price Unit')->width('120px'),
                                    ])
                                    ->schema([
                                        Forms\Components\Select::make('product_id')
                                            ->label(__('Product'))
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
                                            ->label(__('Quantity'))
                                            ->default(1)
                                            ->live()
                                            ->dehydrated()
                                            ->required(),
                                        Forms\Components\TextInput::make('unit_cost')
                                            ->label(__('Parchuse Price'))
                                            ->live()
                                            ->dehydrated()
                                            ->readOnly()
                                            ->required(),
                                    ])
                                    ->defaultItems(0)
                                    ->reorderable()
                                    ->columnSpan('full'),
                            ])->columnSpan(9),
                        Forms\Components\Card::make()
                            ->schema([
                                Forms\Components\TextInput::make('purchase_number')
                                    ->label(__('Purchase Number'))
                                    ->required()
                                    ->dehydrated()
                                    ->default('ORDCMP-'.now()->format('Ymd').'-'.rand(1000, 99999999))
                                    ->maxLength(255),
                                Forms\Components\Select::make('status')
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

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('supplier.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('total_amount')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->searchable(),
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
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
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
