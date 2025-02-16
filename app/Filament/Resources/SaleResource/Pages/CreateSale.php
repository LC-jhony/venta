<?php

namespace App\Filament\Resources\SaleResource\Pages;

use App\Models\CashRegister;
use App\Models\CashRegisterTotal;
use Illuminate\Support\Facades\Auth;
use App\Filament\Resources\SaleResource;
use Filament\Resources\Pages\CreateRecord;

class CreateSale extends CreateRecord
{
    protected static string $resource = SaleResource::class;


    protected function afterCreate(): void
    {
        $saleTotal = $this->record->total;

        $user = Auth::user();
        $cashRegister = CashRegister::where('user_id', $user->id)
            ->where('status', true) // Changed from 'true' to boolean true
            ->first();
        if ($cashRegister) {
            CashRegisterTotal::create([
                'cash_register_id' => $cashRegister->id,
                'sale_total' => $saleTotal,
                'purchase_total' => 0
            ]);
        }
    }
}
