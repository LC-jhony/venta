<?php

namespace App\Filament\Resources;

use App\Filament\Resources\QuoteResource\Pages;
use App\Models\Product;
use App\Models\Quote;
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

class QuoteResource extends Resource
{
    protected static ?string $model = Quote::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-check';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('User Information')
                    ->schema([
                        Forms\Components\Grid::make()
                            ->schema([
                                Forms\Components\Select::make('user_id')
                                    ->relationship(
                                        name: 'user',
                                        titleAttribute: 'name',
                                        modifyQueryUsing: fn (Builder $query) => $query->latest()
                                    )
                                    ->default(auth()->id())
                                    ->disabled()
                                    ->dehydrated(true)
                                    ->required(),
                                Forms\Components\TextInput::make('serial_number')
                                    ->default(function () {
                                        $serialNumber = Quote::orderBy('serial_number', 'desc')->first();
                                        $prefix = 'Cot_PE '.date('y').'-';
                                        if ($serialNumber && str_starts_with($serialNumber->serial_number, $prefix)) {
                                            $number = intval(substr($serialNumber->serial_number, -5)) + 1;
                                        } else {
                                            $number = 1;
                                        }

                                        return $prefix.str_pad($number, 5, '0', STR_PAD_LEFT);
                                    })
                                    ->disabled()
                                    ->dehydrated(true),

                            ])->columns(2),

                    ]),
                Forms\Components\Group::make()
                    ->columnSpanFull()
                    ->schema([
                        Forms\Components\Group::make()
                            ->schema([
                                TableRepeater::make('DetailQuote')
                                    ->relationship()
                                    ->label('')
                                    ->headers([
                                        Header::make('description'),
                                        Header::make('quantity')->width('120px'),
                                        Header::make('Price Unit')->width('120px'),
                                        Header::make('Total Price')->width('120px'),
                                    ])
                                    ->schema([
                                        Forms\Components\Select::make('product_id')
                                            ->label('Producto')
                                            ->relationship('product', 'name')

                                            // Disable options that are already selected in other rows
                                            ->disableOptionWhen(function ($value, $state, Get $get) {
                                                return collect($get('../*.product_id'))
                                                    ->reject(fn ($id) => $id == $state)
                                                    ->filter()
                                                    ->contains($value);
                                            })
                                            ->getOptionLabelFromRecordUsing(
                                                fn ($record) => "{$record->name} - S/. {$record->purchase_price}"
                                            )
                                            ->searchable(['name'])
                                            ->preload()
                                            ->live()
                                            ->afterStateUpdated(function ($state, Set $set, Get $get) {
                                                if ($state) {
                                                    $product = Product::find($state);
                                                    $set('price_unit', $product->purchase_price);
                                                    $newTotalPrice = bcmul((string) $get('quantity', 1), (string) $product->purchase_price, 2);
                                                    $set('total_price', $newTotalPrice);
                                                }
                                            })
                                            ->required(),
                                        Forms\Components\TextInput::make('quantity')
                                            ->numeric()

                                            ->live()
                                            ->afterStateUpdated(function ($state, Set $set, Get $get) {
                                                $newTotalPrice = bcmul((string) $state, (string) $get('price_unit', 0), 2);
                                                $set('total_price', $newTotalPrice);
                                            })
                                            ->required(),
                                        Forms\Components\TextInput::make('price_unit')
                                            ->label('Price Unit')
                                            ->live()
                                            ->afterStateUpdated(function ($state, Set $set, Get $get) {
                                                $newTotalPrice = bcmul((string) $state, (string) $get('quantity', 0), 2);
                                                $set('total_price', $newTotalPrice);
                                            })
                                            ->required(),
                                        Forms\Components\TextInput::make('total_price')
                                            ->disabled()
                                            ->numeric()
                                            ->dehydrated(true)
                                            ->required()
                                            ->reactive(),

                                    ])
                                    ->defaultItems(0)
                                    ->reorderable()
                                    ->columnSpan('full'),
                            ])->columnSpan(9),
                        Forms\Components\Card::make()
                            ->schema([
                                Forms\Components\Select::make('supplier_id')
                                    ->relationship('supplier', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->required()
                                    ->native(false),

                            ])->columnSpan(3),
                    ])
                    ->columns(12),
                Forms\Components\Textarea::make('notes')
                    ->required()
                    ->columnSpanFull(),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->sortable(),
                Tables\Columns\TextColumn::make('serial_number')
                    ->sortable(),
                Tables\Columns\TextColumn::make('supplier.name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('supplier.email')
                    ->label('Mail')
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

                // Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\ForceDeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
                Tables\Actions\Action::make('View_Invoice')
                    ->label(__('View Invoice'))
                    ->icon('lineawesome-file-pdf')
                    ->color('success')
                    ->url(fn ($record) => self::getUrl('invoice', ['record' => $record->id])),

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
            // 'view' => Pages\ViewQuote::route('/{record}'),
            'edit' => Pages\EditQuote::route('/{record}/edit'),
            'invoice' => Pages\Invoice::route('/{record}/invoice'),
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
