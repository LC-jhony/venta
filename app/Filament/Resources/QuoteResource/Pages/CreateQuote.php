<?php

namespace App\Filament\Resources\QuoteResource\Pages;

use App\Filament\Resources\QuoteResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateQuote extends CreateRecord
{
    protected static string $resource = QuoteResource::class;
    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('invoice', [
            'record' => $this->record
        ]);
    }
}
