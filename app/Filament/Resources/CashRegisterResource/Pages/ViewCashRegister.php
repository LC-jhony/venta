<?php

namespace App\Filament\Resources\CashRegisterResource\Pages;

use Filament\Actions;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\Grid;
use Filament\Resources\Pages\ViewRecord;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use App\Filament\Resources\CashRegisterResource;
use Filament\Infolists\Components\Card;
use Icetalker\FilamentTableRepeatableEntry\Infolists\Components\TableRepeatableEntry;

class ViewCashRegister extends ViewRecord
{
    protected static string $resource = CashRegisterResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make()
                    ->schema([
                        Grid::make()
                            ->schema([
                                TextEntry::make('user.name')
                                    ->label(__('User')),
                                TextEntry::make('initial_amount')
                                    ->label(__('Initial Amount')),
                                TextEntry::make('final_amount')
                                    ->label(__('Final Amount')),
                                TextEntry::make('open_date')
                                    ->label(__('Open Date')),
                                TextEntry::make('close_date')
                                    ->label(__('Close Date')),
                                IconEntry::make('status')
                                    ->label(__('Status'))
                                    ->boolean()
                                // ->color(fn(string $state): string => match ($state) {
                                //     'true' => 'success',
                                //     'false' => 'danger',
                                //     default => 'primary',
                                // })
                            ])
                            ->columns(3),
                        TextEntry::make('notes')
                            ->label(__('Notes')),

                    ])
         
            ]);
    }
}
