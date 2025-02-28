<?php

namespace App\Filament\Resources\QuoteResource\Pages;

use App\Filament\Resources\QuoteResource;
use App\Mail\QuoteShippedMail;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Mail;

class EditQuote extends EditRecord
{
    protected static string $resource = QuoteResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }

    protected function afterSave(): void
    {
        $quote = $this->record;

        if ($quote->status) {
            // Cargar la relación de proveedores
            $quote->load('suppliers');

            // Verificar si hay proveedores asociados
            if ($quote->suppliers->isNotEmpty()) {
                // Obtener todos los correos de los proveedores
                $supplierEmails = $quote->suppliers->pluck('email')->filter()->toArray();

                if (! empty($supplierEmails)) {
                    // Enviar correo a todos los proveedores seleccionados
                    Mail::to($supplierEmails)->send(new QuoteShippedMail($quote));

                    Notification::make()
                        ->title('Correo enviado')
                        ->body('Se ha enviado la cotización actualizada por correo electrónico a '.count($supplierEmails).' proveedores.')
                        ->success()
                        ->send();
                } else {
                    Notification::make()
                        ->title('No se pudo enviar el correo')
                        ->body('Los proveedores seleccionados no tienen correos electrónicos registrados.')
                        ->warning()
                        ->send();
                }
            } else {
                Notification::make()
                    ->title('No se pudo enviar el correo')
                    ->body('No hay proveedores asociados a esta cotización.')
                    ->warning()
                    ->send();
            }
        } else {
            Notification::make()
                ->title('Cotización actualizada')
                ->body('La cotización ha sido actualizada pero no se ha enviado por correo porque está inactiva.')
                ->info()
                ->send();
        }
    }
}
