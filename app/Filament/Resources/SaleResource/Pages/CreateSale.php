<?php

namespace App\Filament\Resources\SaleResource\Pages;

use App\Filament\Resources\SaleResource;
use App\Models\CashMovement;
use App\Models\CashRegister;
use Filament\Notifications\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateSale extends CreateRecord
{
    protected static string $resource = SaleResource::class;

    public function beforeCreate(): void
    {
        $this->validateCashRegisterStatus();
    }

    protected function afterCreate(): void
    {
        $this->createCashRegisterTotal();
    }

    private function validateCashRegisterStatus(): void
    {
        $cashRegister = $this->getCurrentUserCashRegister();

        if (! $cashRegister) {
            $this->notifyCashRegisterRequired();
        }

        if (! $cashRegister?->status) {
            $this->notifyCashRegisterClosed();
        }
    }

    private function getCurrentUserCashRegister(): ?CashRegister
    {
        return CashRegister::where('user_id', Auth::id())
            ->where('status', true)
            ->latest()
            ->first();
    }

    private function createCashRegisterTotal(): void
    {
        $cashRegister = $this->getCurrentUserCashRegister();

        if (! $cashRegister) {
            return;
        }

        CashMovement::create([
            'cash_register_id' => $cashRegister->id,
            'type' => 'Salida',
            'amount' => $this->record->total,

        ]);
    }

    private function notifyCashRegisterRequired(): void
    {

        Notification::make()
            ->title('Cash Register Required')
            ->body('You must open a cash register before creating a sale.')
            ->actions([
                Action::make('Open Cash Register')
                    ->button()

                    ->url(route('filament.admin.resources.cash-registers.create'), shouldOpenInNewTab: true),

            ])
            ->danger()
            ->send();

        $this->halt();
    }

    private function notifyCashRegisterClosed(): void
    {
        Notification::make()
            ->title('Cash Register Closed')
            ->body('The cash register has been closed. Please open a new one.')
            ->danger()
            ->send();
        $this->halt();
    }
}
