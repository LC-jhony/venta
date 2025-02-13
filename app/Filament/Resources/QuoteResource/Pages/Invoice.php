<?php

namespace App\Filament\Resources\QuoteResource\Pages;

use App\Filament\Resources\QuoteResource;
use App\Models\Quote;
use Filament\Pages\Actions\Action;
use Filament\Resources\Pages\Page;

class Invoice extends Page
{
    protected static string $resource = QuoteResource::class;

    public $record;

    public $quote;

    public function mount($record)
    {
        $this->record = $record;
        $this->quote = Quote::with('detailQuote.product')
            ->find($record);
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('print')
                ->icon('solar-printer-outline')
                ->outlined()
                ->url(
                    route(
                        'PRINT.INVOICE-QUOTE',
                        ['quote' => $this->record]
                    )
                )->requiresConfirmation(),
        ];
    }

    protected static string $view = 'filament.resources.quote-resource.pages.invoice';
}
