<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Quote;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Awcodes\TableRepeater\Header;
use Filament\Tables\Actions\ActionGroup;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\QuoteResource\Pages;
use Awcodes\TableRepeater\Components\TableRepeater;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\QuoteResource\RelationManagers;

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
                        Forms\Components\Grid::make(3)
                            ->schema([
                                Forms\Components\TextInput::make('code')
                                    ->label('Code Bar')
                                    ->prefixIcon('fas-barcode')
                                    ->nullable(),
                                Forms\Components\Select::make('user_id')
                                    ->relationship(
                                        name: 'user',
                                        titleAttribute: 'name',
                                        modifyQueryUsing: fn(Builder $query) => $query->latest()
                                    )
                                    ->default(auth()->id())
                                    ->disabled()
                                    ->dehydrated(true)
                                    ->required(),
                                Forms\Components\TextInput::make('serial_number')
                                    ->numeric()
                                    ->default(null),
                                Forms\Components\TextInput::make('serial')
                                    ->maxLength(255)
                                    ->default(null),
                            ]),
                    ]),
                Forms\Components\Group::make()
                    ->columnSpanFull()
                    ->schema([
                        Forms\Components\Group::make()
                            ->schema([
                                TableRepeater::make('items')
                                    ->headers([
                                        Header::make('description'),
                                        Header::make('quantity')->width('150px'),
                                        Header::make('unit_price')->width('150px'),
                                    ])
                                    ->schema([
                                        Forms\Components\TextInput::make('description')
                                            ->required(),
                                        Forms\Components\TextInput::make('quantity')
                                            ->default(1)
                                            ->required()
                                            ->numeric(),
                                        Forms\Components\TextInput::make('unit_price')
                                            ->default(10000)
                                            ->required()
                                            ->live()
                                            ->numeric(),
                                    ])
                                    ->item
                                    ->addActionLabel('Add Item')
                                    ->columns(3)
                            ]),
                    ]),
                Forms\Components\Textarea::make('notes')
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\Toggle::make('mail')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('serial_number')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('serial')
                    ->searchable(),
                Tables\Columns\IconColumn::make('mail')
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

                // Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\ForceDeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
                Tables\Actions\Action::make('View_Invoice')
                    ->label(__('View Invoice'))
                    ->icon('lineawesome-file-pdf')
                    ->color('success')
                    ->url(fn($record) => self::getUrl('invoice', ['record' => $record->id])),

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
