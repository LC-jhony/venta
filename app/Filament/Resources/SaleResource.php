<?php

namespace App\Filament\Resources;

use App\Enum\Sale\TypePyment;
use App\Enum\Sale\TypeSale;
use App\Enum\Sale\TypeStatus;
use Filament\Forms;
use App\Models\Sale;
use Filament\Tables;
use App\Models\Product;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Awcodes\TableRepeater\Header;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\SaleResource\Pages;
use Awcodes\TableRepeater\Components\TableRepeater;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\SaleResource\RelationManagers;
use Pelmered\FilamentMoneyField\Forms\Components\MoneyInput;

class SaleResource extends Resource
{
    protected static ?string $model = Sale::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Compras / Ventas';
    protected static ?string $recordTitleAttribute = 'sale_number'; //para que se pueda buscar de manera global

    protected static ?string $activeNavigationIcon = 'heroicon-o-check-badge'; //cambiar el icono de la seccion activa

    public static function form(Form $form): Form
    {
        $products = Product::get();
        return $form
            ->schema([
                Forms\Components\Section::make('Sale Information')
                    ->schema([
                        Forms\Components\Grid::make()
                            ->schema([
                                Forms\Components\TextInput::make('code')
                                    ->autofocus(true)
                                    ->suffixIcon('ri-barcode-line')
                                    ->live(onBlur: true)
                                    ->reactive()
                                    ->afterStateUpdated(function ($state, $set, $get) {
                                        if ($state) {
                                            $product = Product::where('bar_code', $state)->first();
                                            if ($product) {
                                                $currentDetails = $get('saleDetails') ?? [];
                                                $currentDetails[] = [
                                                    'product_id' => $product->id,
                                                    'quantity' => 1,
                                                    'unit_price' => $product->sales_price,
                                                    'total' => $product->sales_price
                                                ];
                                                $set('saleDetails', $currentDetails);
                                                $set('code', '');
                                                self::calculateSaleTotals($get('saleDetails'), $set);
                                            }
                                        }
                                    }),
                                Forms\Components\TextInput::make('customer_id')
                                    ->required()
                                    ->numeric(),
                                Forms\Components\TextInput::make('invoice_number')
                                    ->default('INV-' . date('Ymd-His'))
                                    ->required()
                                    ->maxLength(255),
                            ])->columns(3),
                        Forms\Components\Grid::make()
                            ->schema([
                                Forms\Components\Select::make('payment_method')
                                    ->options(TypePyment::class),
                                Forms\Components\Select::make('payment_status')
                                    ->options(TypeSale::class),
                                Forms\Components\Select::make('sale_status')
                                    ->options(TypeStatus::class),
                            ])->columns(3)
                    ]),
                Forms\Components\Group::make()
                    ->columnSpanFull()
                    ->schema([
                        Forms\Components\Group::make()
                            ->schema([
                                TableRepeater::make('saleDetails')
                                    ->label('')
                                    ->relationship()
                                    ->live()
                                    ->afterStateUpdated(function (Get $get, Set $set) {
                                        $details = $get('saleDetails') ?? [];
                                        self::calculateSaleTotals($details, $set);
                                    })
                                    ->headers([
                                        Header::make('description'),
                                        Header::make('quantity')->width('90px'),
                                        Header::make('Price Unit')->width('90px'),
                                        Header::make('total')->width('90px'),
                                    ])
                                    ->schema([
                                        Forms\Components\Select::make('product_id')
                                            ->relationship('product', 'name')
                                            ->reactive()
                                            ->afterStateUpdated(function ($state, callable $set, Get $get) {
                                                self::calculateLineTotal($get('quantity'), $state, $set);
                                                self::calculateSaleTotals($get('../'), $set);
                                            }),
                                        Forms\Components\TextInput::make('quantity')
                                            ->numeric()
                                            ->reactive()
                                            ->afterStateUpdated(function ($state, callable $set, Get $get) {
                                                self::calculateLineTotal($state, $get('product_id'), $set);
                                                self::calculateSaleTotals($get('../'), $set);
                                            }),

                                        Forms\Components\TextInput::make('unit_price')
                                            ->disabled(),

                                        Forms\Components\TextInput::make('total_price')
                                            ->disabled(),
                                    ])
                                    ->reorderable()
                                    ->columnSpan('full')
                            ])->columnSpan(8),

                        Forms\Components\Grid::make()
                            ->schema([
                                Forms\Components\Section::make('Payment Details')
                                    ->schema([

                                        MoneyInput::make('paid_amount')
                                            ->numeric()
                                            ->reactive()
                                            ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                                $paidAmount = floatval($state); // Convertir a número
                                                $total = floatval($get('total') ?? 0); // Obtener total o 0 si es null
                                                $change = max($paidAmount - $total, 0); // Asegurar que no sea negativo
                                                $set('change_amount', number_format($change, 2, '.', ',')); // Establecer el cambio
                                            }),
                                        MoneyInput::make('change_amount')
                                            ->disabled()
                                            ->numeric()
                                            ->live(),
                                    ]),
                                Forms\Components\Section::make('totales')
                                    ->schema([
                                        MoneyInput::make('subtotal')
                                            ->disabled(),
                                        MoneyInput::make('tax')
                                            ->disabled(),
                                        MoneyInput::make('total')
                                            ->disabled()
                                            ->live(),
                                    ])


                            ])->columnSpan(4),
                    ])
                    ->columns(12),

                Forms\Components\Textarea::make('notes')
                    ->columnSpanFull()

            ]);
    }
    private static function calculateSaleTotals($saleDetails, callable $set)
    {
        $subtotal = 0;
        $tax = 0;

        foreach ($saleDetails as $detail) {
            $subtotal += $detail['quantity'] * $detail['unit_price'];
        }

        $tax = $subtotal * 0.18; // 18% tax rate
        $total = $subtotal + $tax;

        $set('subtotal', round($subtotal, 2));
        $set('tax', round($tax, 2));
        $set('total', round($total, 2));
    }
    private static function calculateLineTotal($quantity, $productId, callable $set)
    {
        $product = Product::find($productId);
        if ($product) {
            $totalPrice = $quantity * $product->sales_price;
            $set('unit_price', $product->sales_price);
            $set('total_price', round($totalPrice, 2));
            return $totalPrice;
        }
        return 0;
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('customer_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('invoice_number')
                    ->searchable(),
                Tables\Columns\TextColumn::make('subtotal')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('tax')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('total')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('paid_amount')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('change_amount')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('payment_method'),
                Tables\Columns\TextColumn::make('payment_status'),
                Tables\Columns\TextColumn::make('sale_status'),
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
            'index' => Pages\ListSales::route('/'),
            'create' => Pages\CreateSale::route('/create'),
            'view' => Pages\ViewSale::route('/{record}'),
            'edit' => Pages\EditSale::route('/{record}/edit'),
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
