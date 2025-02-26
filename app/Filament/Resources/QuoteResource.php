<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Quote;
use App\Models\Product;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Awcodes\TableRepeater\Header;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\QuoteResource\Pages;
use Awcodes\TableRepeater\Components\TableRepeater;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class QuoteResource extends Resource
{
    protected static ?string $model = Quote::class;

    protected static ?string $navigationIcon = 'heroicon-o-inbox-stack';

    protected static ?string $navigationGroup = 'Sistem POS';
    protected static ?string $modelLabel = 'Cotizacióne';

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Información de Cotización')
                    ->schema([
                        Forms\Components\Grid::make()
                            ->schema([
                                Forms\Components\Select::make('user_id')
                                    ->label('Usuario')
                                    ->relationship(
                                        name: 'user',
                                        titleAttribute: 'name',
                                        modifyQueryUsing: fn(Builder $query) => $query->latest()
                                    )
                                    ->default(Auth::user()->id)
                                    ->disabled()
                                    ->dehydrated(true)
                                    ->required(),
                                Forms\Components\TextInput::make('number_quote')
                                    ->label('Número de cotización')
                                    ->required()
                                    ->default(function () {
                                        $serialNumber = Quote::orderBy('number_quote', 'desc')->first();
                                        $prefix = 'Cot_PE ' . date('y') . '-';
                                        if ($serialNumber && str_starts_with($serialNumber->number_quote, $prefix)) {
                                            $number = intval(substr($serialNumber->number_quote, -5)) + 1;
                                        } else {
                                            $number = 1;
                                        }

                                        return $prefix . str_pad($number, 5, '0', STR_PAD_LEFT);
                                    })
                                    ->disabled()
                                    ->dehydrated(true),
                                Forms\Components\DatePicker::make('valid_date')
                                    ->label('Fecha de vencimiento')
                                    ->required()
                                    ->native(false),
                            ])
                            ->columns([
                                'default' => 1,
                                'sm' => 2,
                                'md' => 3,
                                'lg' => 3,
                            ]),
                    ]),

                Forms\Components\Section::make('Detalles de la Cotización')
                    ->schema([
                        Forms\Components\Grid::make()
                            ->schema([
                                Forms\Components\Group::make()
                                    ->schema([
                                        TableRepeater::make('quoteProducts')
                                            ->relationship()
                                            ->label('')
                                            ->live()
                                            ->emptyLabel('Seleccione un producto')
                                            ->afterStateUpdated(function (Get $get, Set $set) {
                                                $details = $get('quoteProducts') ?? [];
                                                self::calculateQuoteTotal($details, $set);
                                            })
                                            ->headers([
                                                Header::make('Descripción'),
                                                Header::make('Cantidad')->width('120px'),
                                                Header::make('Pre. Unitario')->width('120px'),
                                                Header::make('Pre. Total')->width('120px'),
                                            ])
                                            ->schema([
                                                Forms\Components\Select::make('product_id')
                                                    ->label('Producto')
                                                    ->relationship('product', 'name')
                                                    ->required()
                                                    ->disableOptionWhen(function ($value, $state, Get $get) {
                                                        return collect($get('../*.product_id'))
                                                            ->reject(fn($id) => $id == $state)
                                                            ->filter()
                                                            ->contains($value);
                                                    })
                                                    ->getOptionLabelFromRecordUsing(
                                                        fn($record) => "{$record->name} - S/. {$record->purchase_price}"
                                                    )
                                                    ->searchable(['name'])
                                                    ->preload()
                                                    ->reactive()
                                                    ->afterStateUpdated(function ($state, callable $set, Get $get) {
                                                        self::calculateLineTotal($get('quantity'), $state, $set);
                                                        self::calculateQuoteTotal($get('../'), $set);
                                                    }),
                                                Forms\Components\TextInput::make('quantity')
                                                    ->numeric()
                                                    ->reactive()
                                                    ->required()
                                                    ->afterStateUpdated(function ($state, callable $set, Get $get) {
                                                        self::calculateLineTotal($state, $get('product_id'), $set);
                                                        self::calculateQuoteTotal($get('../'), $set);
                                                    }),
                                                Forms\Components\TextInput::make('price_unit')
                                                    ->label('Precio Unitario')
                                                    ->live()
                                                    ->required(),
                                                Forms\Components\TextInput::make('total_price')
                                                    ->label('Precio Total')
                                                    ->disabled()
                                                    ->numeric()
                                                    ->dehydrated(true)
                                                    ->required()
                                                    ->reactive(),
                                            ])
                                            ->defaultItems(0)
                                            ->reorderable()
                                            ->columnSpan('full'),
                                    ])
                                    ->columnSpan([
                                        'default' => 'full',
                                        'md' => 8,
                                        'lg' => 9,
                                    ]),

                                Forms\Components\Card::make()
                                    ->schema([
                                        Forms\Components\Select::make('status')
                                            ->label('Estado')
                                            ->options([
                                                '1' => 'Aceptado',
                                                '0' => 'Rechazado',
                                            ])
                                            ->required()
                                            ->native(false),
                                        Forms\Components\TextInput::make('total')
                                            ->label('Total')
                                            ->disabled()
                                            ->numeric()
                                            ->prefix('S/. ')
                                            ->dehydrated(true)
                                            ->required(),
                                        Forms\Components\Select::make('suppliers')
                                            ->label('Proveedores')
                                            ->relationship('suppliers', 'name')
                                            ->multiple()
                                            ->searchable()
                                            ->preload()
                                            ->required()
                                            ->native(false),
                                    ])
                                    ->columnSpan([
                                        'default' => 'full',
                                        'md' => 4,
                                        'lg' => 3,
                                    ]),
                            ])
                            ->columns(12),
                    ]),

                Forms\Components\Grid::make()
                    ->schema([
                        Forms\Components\Textarea::make('notes')
                            ->required()
                            ->rows(3)
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    private static function calculateQuoteTotal($quoteProducts, callable $set)
    {

        $total = 0;
        foreach ($quoteProducts as $detail) {
            $total += $detail['quantity'] * $detail['price_unit'];
        }
        $set('total', number_format($total, 2, '.', ''));
    }
    private static function calculateLineTotal($quantity, $productId, callable $set)
    {
        $product = Product::find($productId);
        if ($product) {
            $totalPrice = $quantity * $product->purchase_price;
            $set('price_unit', $product->purchase_price);
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
                    ->label('Usuario')
                    ->sortable(),
                Tables\Columns\TextColumn::make('number_quote')
                    ->label('Codigo')
                    ->sortable(),
                Tables\Columns\TextColumn::make('valid_date')
                    ->label('Fecha de vencimiento')
                    ->sortable(),
                Tables\Columns\TextColumn::make('total')
                    ->label('Total')
                    ->money()
                    ->sortable(),
                Tables\Columns\IconColumn::make('status')
                    ->label('Estado')
                    ->boolean(),
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
                        '1' => 'Aceptado',
                        '0' => 'Rechazado',
                    ]),
                Tables\Filters\SelectFilter::make('suppliers')
                    ->label('Proveedor')
                    ->relationship('suppliers', 'name')
                    ->searchable()
                    ->preload()
                    ->native(false),
                Tables\Filters\SelectFilter::make('user_id')
                    ->label('Usuario')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload()
                    ->native(false),
                Tables\Filters\Filter::make('valid_date')
                    ->label('Fecha de vencimiento')
                    ->form([
                        Forms\Components\DatePicker::make('valid_date'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                        ->when(
                            $data['valid_date'],
                            fn(Builder $query, $date): Builder => $query->whereDate('valid_date', $date)
                        );
                    }),
            ])
            ->actions([

                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\ForceDeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
                Tables\Actions\Action::make('pdf')
                    ->label('Pdf')
                    ->icon('lineawesome-file-pdf')
                    ->url(fn($record): string => route('QUOTE-INVOICE', $record->id))
                    ->openUrlInNewTab()
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
            'index' => Pages\ListQuotes::route('/'),
            'create' => Pages\CreateQuote::route('/create'),
            'view' => Pages\ViewQuote::route('/{record}'),
            'edit' => Pages\EditQuote::route('/{record}/edit'),
            //'invoice' => Pages\Invoice::route('/{record}/invoice'),
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
