<?php

namespace App\Filament\Resources\PurchaseResource\Pages;

use Filament\Actions;
use App\Models\Product;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\PurchaseResource;
use Illuminate\Support\Collection;

class EditPurchase extends EditRecord
{
    protected static string $resource = PurchaseResource::class;


    protected ?Collection $originalDetails = null;

    protected function beforeFill(): void
    {
        // Asegurarnos de que detailparchuse está cargado
        if ($this->record && $this->record->relationLoaded('detailparchuse')) {
            $this->originalDetails = $this->record->detailparchuse->map(function ($detail) {
                return [
                    'product_id' => $detail->product_id,
                    'quantity' => $detail->quantity,
                    'product_name' => $detail->product->name,
                    'status' => $this->record->status
                ];
            });
        }
    }

    protected function afterSave(): void
    {
        $purchase = $this->record;
        $originalStatus = $this->originalDetails?->first()['status'] ?? null;

        // Si el estado es rechazado
        if ($purchase->status === '0') {
            foreach ($purchase->detailparchuse as $detail) {
                $product = Product::find($detail->product_id);
                if ($product) {
                    $oldStock = $product->stock;
                    $product->stock -= $detail->quantity;
                    $product->save();

                    // Notificación detallada para cada producto
                    Notification::make()
                        ->title("Stock Actualizado: {$product->name}")
                        ->body("Se restaron {$detail->quantity} unidades del stock.\n" .
                            "Stock anterior: {$oldStock}\n" .
                            "Nuevo stock: {$product->stock}")
                        ->success()
                        ->send();
                }
            }

            // Notificación resumen para estado rechazado
            $totalProducts = $purchase->detailparchuse->count();
            $totalQuantity = $purchase->detailparchuse->sum('quantity');

            Notification::make()
                ->title('Compra Rechazada')
                ->body("Se han actualizado {$totalProducts} productos.\n" .
                    "Total de unidades restadas: {$totalQuantity}")
                ->warning()
                ->persistent()
                ->send();
        }
        // Si el estado es aceptado
        elseif ($purchase->status === '1') {
            foreach ($purchase->detailparchuse as $detail) {
                $product = Product::find($detail->product_id);
                if ($product) {
                    $oldStock = $product->stock;

                    // Si el estado anterior era rechazado, necesitamos ajustar el stock
                    if ($originalStatus === '0') {
                        $product->stock += ($detail->quantity * 2); // Compensamos la resta anterior y sumamos
                    } else {
                        $product->stock += $detail->quantity;
                    }

                    $product->save();

                    // Notificación detallada para cada producto
                    Notification::make()
                        ->title("Stock Actualizado: {$product->name}")
                        ->body("Se agregaron {$detail->quantity} unidades al stock.\n" .
                            "Stock anterior: {$oldStock}\n" .
                            "Nuevo stock: {$product->stock}")
                        ->success()
                        ->send();
                }
            }

            // Notificación resumen para estado aceptado
            $totalProducts = $purchase->detailparchuse->count();
            $totalQuantity = $purchase->detailparchuse->sum('quantity');

            Notification::make()
                ->title('Compra Aceptada')
                ->body("Se han actualizado {$totalProducts} productos.\n" .
                    "Total de unidades agregadas: {$totalQuantity}")
                ->success()
                ->persistent()
                ->send();
        }
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }
}
