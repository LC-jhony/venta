<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SaleResource\Pages;
use App\Models\Product;
use App\Models\Sale;
use Awcodes\TableRepeater\Components\TableRepeater;
use Awcodes\TableRepeater\Header;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;
use Pelmered\FilamentMoneyField\Forms\Components\MoneyInput;

class SaleResource extends Resource
{
    protected static ?string $model = Sale::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Compras / Ventas';

    protected static ?string $recordTitleAttribute = 'sale_number'; // para que se pueda buscar de manera global

    protected static ?string $activeNavigationIcon = 'heroicon-o-check-badge'; // cambiar el icono de la seccion activa

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

                                                $found = false;
                                                foreach ($currentDetails as $key => $detail) {
                                                    if ($detail['product_id'] === $product->id) {
                                                        $quantity = floatval($currentDetails[$key]['quantity']) + 1;
                                                        $unitPrice = floatval($currentDetails[$key]['unit_price']);

                                                        $currentDetails[$key]['quantity'] = $quantity;
                                                        $currentDetails[$key]['total_price'] = $quantity * $unitPrice;
                                                        $found = true;
                                                        break;
                                                    }
                                                }

                                                if (! $found) {
                                                    $currentDetails[] = [
                                                        'product_id' => $product->id,
                                                        'quantity' => 1,
                                                        'unit_price' => floatval($product->sales_price),
                                                        'total_price' => floatval($product->sales_price),
                                                    ];
                                                }

                                                $set('saleDetails', $currentDetails);
                                                $set('code', '');
                                                self::calculateSaleTotals($get('saleDetails'), $set);
                                            }
                                        }
                                    }),
                                Forms\Components\Select::make('customer_id')
                                    ->relationship('customer', 'name')
                                    ->required()
                                    ->searchable()
                                    ->preload()
                                    ->native(false),
                                Forms\Components\Select::make('user_id')
                                    ->relationship('user', 'name')
                                    ->default(Auth::id() ?? 1)
                                    ->required()
                                    ->disabled()
                                    ->dehydrated(),
                                Forms\Components\TextInput::make('invoice_number')
                                    ->default('INV-'.date('Ymd-His'))
                                    ->required()
                                    ->maxLength(255),
                            ])->columns(4),
                        Forms\Components\Grid::make()
                            ->schema([
                                Forms\Components\Textarea::make('notes')
                                    ->columnSpanFull(),
                            ])->columns(3),
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
                                            ->searchable()
                                            ->preload()
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
                                            ->required()
                                            ->disabled()
                                            ->dehydrated(true),

                                        Forms\Components\TextInput::make('total_price')
                                            ->required()
                                            ->disabled()
                                            ->dehydrated(true),
                                    ])
                                    ->defaultItems(0)
                                    ->reorderable()
                                    ->columnSpan('full'),
                            ])->columnSpan(8),

                        Forms\Components\Grid::make()
                            ->schema([
                                // Forms\Components\Section::make('Payment Details')
                                //     ->schema([
                                //         MoneyInput::make('paid_amount')
                                //             ->required()
                                //             ->numeric()
                                //             ->reactive()
                                //             ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                //                 $paidAmount = floatval($state); // Convertir a número
                                //                 $total = floatval($get('total') ?? 0); // Obtener total o 0 si es null
                                //                 $change = max($paidAmount - $total, 0); // Asegurar que no sea negativo
                                //                 $set('change_amount', number_format($change, 2, '.', ',')); // Establecer el cambio
                                //             }),
                                //         MoneyInput::make('change_amount')
                                //             ->required()
                                //             ->disabled()
                                //             ->dehydrated(true)
                                //             ->numeric()
                                //             ->live(),
                                //     ]),
                                Forms\Components\Section::make('totales')
                                    ->schema([
                                        Forms\Components\TextInput::make('subtotal')
                                            ->disabled()
                                            ->dehydrated(true),
                                        Forms\Components\TextInput::make('tax')
                                            ->disabled()
                                            ->dehydrated(true),
                                        Forms\Components\TextInput::make('total')
                                            ->disabled()
                                            ->dehydrated(true),
                                    ]),

                            ])->columnSpan(4),
                    ])
                    ->columns(12),

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
                Tables\Columns\TextColumn::make('user.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('customer.name')
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
                    ->numeric(),

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
