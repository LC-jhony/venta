<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SaleResource\Pages;
use App\Models\Product;
use App\Models\Sale;
use Awcodes\TableRepeater\Components\TableRepeater;
use Awcodes\TableRepeater\Header;
use Filament\Forms;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class SaleResource extends Resource
{
    protected static ?string $model = Sale::class;

    protected static ?string $navigationIcon = 'gmdi-point-of-sale-tt';

    protected static ?string $navigationGroup = 'Parchuse / Sale';

    // protected static ?string $recordTitleAttribute = 'sale_number'; // para que se pueda buscar de manera global

    // protected static ?string $activeNavigationIcon = 'heroicon-o-check-badge';
    protected static ?string $modelLabel = 'Venta';

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

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
                                    ->label('Codigo Barra')
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
                                    ->label('Cliente')
                                    ->relationship('customer', 'name')
                                    ->required()
                                    ->searchable()
                                    ->preload()
                                    ->native(false)
                                    ->createOptionForm([
                                        Forms\Components\Grid::make()
                                            ->schema([
                                                Forms\Components\TextInput::make('name')
                                                    ->label('Nombre')
                                                    ->required()
                                                    ->maxLength(255),
                                                Forms\Components\TextInput::make('lastname')
                                                    ->label('Apellido')
                                                    ->required()
                                                    ->maxLength(255),
                                                Forms\Components\TextInput::make('dni')
                                                    ->label('DNI')
                                                    ->required()
                                                    ->maxLength(255),
                                                Forms\Components\TextInput::make('email')
                                                    ->label('Correo Electrónico')
                                                    ->email()
                                                    ->required()
                                                    ->maxLength(255),
                                                Forms\Components\TextInput::make('phone')
                                                    ->label('Teléfono | Celular')
                                                    ->tel()
                                                    ->required()
                                                    ->regex('/^\+?[0-9]{1,4}?[-.\s]?\(?\d{1,3}?\)?[-.\s]?\d{1,4}[-.\s]?\d{1,4}[-.\s]?\d{1,9}$/'),
                                                Forms\Components\TextInput::make('address')
                                                    ->label('Dirección')
                                                    ->required()
                                                    ->maxLength(255),
                                            ]),
                                    ])
                                    ->createOptionAction(function (Action $action) {
                                        return $action
                                            ->modalHeading('Registrar Cliente')
                                            ->modalSubmitActionLabel('Crear')
                                            ->modalWidth('lg');
                                    }),

                                Forms\Components\Select::make('user_id')
                                    ->label('Usuario')
                                    ->relationship('user', 'name')
                                    ->default(Auth::id() ?? 1)
                                    ->required()
                                    ->disabled()
                                    ->dehydrated(),
                                Forms\Components\TextInput::make('invoice_number')
                                    ->label('Número de Factura')
                                    ->default('FAC-'.now()->format('Y').'-'.rand(1000, 999999))
                                    ->required()
                                    ->unique(Sale::class, 'invoice_number', ignoreRecord: true)
                                    ->disabled()
                                    ->dehydrated(),
                            ])->columns([
                                'default' => 1,
                                'sm' => 2,
                                'md' => 3,
                                'lg' => 4,
                            ]),
                        Forms\Components\Grid::make()
                            ->schema([
                                Forms\Components\Textarea::make('notes')
                                    ->label('Notas')
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
                                        Header::make('Descripcion'),
                                        Header::make('Cantidad')->width('90px'),
                                        Header::make('Pre. Unitario')->width('90px'),
                                        Header::make('Total')->width('90px'),
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
                                                $productId = $get('product_id');

                                                if ($productId && $state) {
                                                    $product = Product::find($productId);
                                                    if ($state > $product->stock) {
                                                        Notification::make()
                                                            ->title('Error')
                                                            ->body("Solo hay {$product->stock} unidades disponibles")
                                                            ->danger()
                                                            ->send();
                                                        $set('quantity', $product->stock);

                                                        return;
                                                    }
                                                }
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
                                    ->emptyLabel('Seleccione un producto')
                                    ->defaultItems(0)
                                    ->reorderable()
                                    ->columnSpan('full'),
                            ])->columnSpan([
                                'default' => 'full',
                                'md' => 8,
                                'lg' => 9,
                            ]),

                        Forms\Components\Grid::make()
                            ->schema([
                                Forms\Components\Section::make('Totales')
                                    ->schema([
                                        Forms\Components\TextInput::make('subtotal')
                                            ->label('Subtotal')
                                            ->disabled()
                                            ->dehydrated(true),
                                        Forms\Components\TextInput::make('tax')
                                            ->label('IGV')
                                            ->helperText(str('Impuesto del **18%** del sub Total.')->inlineMarkdown()->toHtmlString())
                                            ->disabled()
                                            ->dehydrated(true),
                                        Forms\Components\TextInput::make('total')
                                            ->label('Total')
                                            ->disabled()
                                            ->dehydrated(true),
                                    ]),

                            ])->columnSpan([
                                'default' => 'full',
                                'md' => 4,
                                'lg' => 3,
                            ]),
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
            ->striped()
            ->paginated([5, 10, 25, 50, 100, 'all'])
            ->defaultPaginationPageOption(5)
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Usuario')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('customer.name')
                    ->label('Cliente')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('invoice_number')
                    ->label('Factura')
                    ->searchable(),
                Tables\Columns\TextColumn::make('subtotal')
                    ->label('Sub. Total')
                    ->money('S/.')
                    ->sortable(),
                Tables\Columns\TextColumn::make('tax')
                    ->label('IGV 18%')
                    ->money('S/.')
                    ->sortable(),
                Tables\Columns\TextColumn::make('total')
                    ->label('Total')
                    ->money('S/.')
                    ->summarize(Sum::make()->money('S/.')),
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

            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\Action::make('invoice')
                    ->label('PDF')
                    ->icon('lineawesome-file-pdf')
                    ->url(fn ($record): string => route('PRINT.INVOICE-SALE', $record->id))
                    ->openUrlInNewTab(),
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
