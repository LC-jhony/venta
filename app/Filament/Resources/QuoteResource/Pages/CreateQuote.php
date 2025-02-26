<?php

namespace App\Filament\Resources\QuoteResource\Pages;

use App\Filament\Resources\QuoteResource;
use App\Mail\QuoteShipped;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Mail;

class CreateQuote extends CreateRecord
{
    protected static string $resource = QuoteResource::class;

    protected function afterCreate(): void
    {
        $quote = $this->record;

        // Verificar si el estado es "Aceptado" (1)
        if ($quote->status == '1') {
            // Obtener los proveedores seleccionados
            $suppliers = $quote->suppliers;

            if ($suppliers->count() > 0) {
                foreach ($suppliers as $supplier) {
                    // Verificar que el proveedor tenga un correo electrónico
                    if ($supplier->email) {
                        try {
                            // Enviar correo electrónico al proveedor
                            Mail::to($supplier->email)
                                ->send(new QuoteShipped($quote, $supplier));
                        } catch (\Exception $e) {
                            // Registrar el error pero continuar con los demás proveedores
                            \Log::error('Error al enviar correo a proveedor: '.$e->getMessage());

                            // Notificar al usuario sobre el error
                            Notification::make()
                                ->title('Error al enviar correo')
                                ->body('No se pudo enviar el correo a '.$supplier->name.': '.$e->getMessage())
                                ->danger()
                                ->send();
                        }
                    }
                }

                // Notificar al usuario que los correos fueron enviados
                Notification::make()
                    ->title('Correos enviados')
                    ->body('Se han enviado correos electrónicos a los proveedores seleccionados.')
                    ->success()
                    ->send();
            }
        }
    }

    // protected function getRedirectUrl(): string
    // {
    //     return static::getResource()::getUrl('index');
    // }

}
