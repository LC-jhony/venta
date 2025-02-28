<?php

namespace App\Filament\Resources\SaleResource\Pages;

use App\Filament\Resources\SaleResource;
use App\Models\Product;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditSale extends EditRecord
{
    protected static string $resource = SaleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }

    protected function beforeSave(): void
    {
        // Obtener los detalles originales antes de la actualización
        $originalDetails = $this->record->saleDetails()->get();

        // Guardar los detalles originales en una propiedad de la clase
        $this->originalDetails = $originalDetails;
    }

    protected function afterSave(): void
    {
        $this->updateProductStock();
    }

    protected function updateProductStock(): void
    {
        $sale = $this->record;
        $newDetails = $sale->fresh()->saleDetails;

        // Crear un mapa de los detalles originales por producto_id
        $originalDetailsMap = $this->originalDetails->keyBy('product_id');

        foreach ($newDetails as $detail) {
            $product = Product::find($detail->product_id);
            if (! $product) {
                continue;
            }

            // Obtener la cantidad original (si existía)
            $originalQuantity = $originalDetailsMap->get($detail->product_id)?->quantity ?? 0;
            $quantityDifference = $detail->quantity - $originalQuantity;

            // Si hay una diferencia, actualizar el stock
            if ($quantityDifference != 0) {
                // Verificar si hay suficiente stock
                if ($product->stock - $quantityDifference < 0) {
                    Notification::make()
                        ->title('Error de stock')
                        ->body("Stock insuficiente para el producto {$product->name}")
                        ->danger()
                        ->send();

                    continue;
                }

                $product->stock -= $quantityDifference;
                $product->save();
            }
        }

        // Manejar productos eliminados (devolver stock)
        foreach ($this->originalDetails as $originalDetail) {
            if (! $newDetails->contains('product_id', $originalDetail->product_id)) {
                $product = Product::find($originalDetail->product_id);
                if ($product) {
                    $product->stock += $originalDetail->quantity;
                    $product->save();

                    Notification::make()
                        ->title('Stock Devuelto')
                        ->body("Se devolvieron {$originalDetail->quantity} 
                        unidades al stock del producto {$product->name}")
                        ->success()
                        ->send();
                }
            }
        }
    }
}
