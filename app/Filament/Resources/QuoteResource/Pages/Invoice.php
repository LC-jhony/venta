<?php

namespace App\Filament\Resources\QuoteResource\Pages;

use Filament\Pages\Actions\Action;
use Filament\Resources\Pages\Page;
use Illuminate\Contracts\View\View;
use App\Filament\Resources\QuoteResource;
use App\Models\Quote;
use Filament\Forms\Contracts\HasForms;

class Invoice extends Page
{
    protected static string $resource = QuoteResource::class;
    public $record;
    public $quote;
    public function mount($record)
    {
        $this->record = $record;
        $this->quote = Quote::with('user')
            ->find($record);
    }


    protected function getHeaderActions(): array
    {
        return [
            Action::make('print')
                ->icon('solar-printer-outline')
                ->outlined()
                ->requiresConfirmation()
                ->url(
                    route(
                        'PRINT.INVOICE-QUOTE',
                        ['quote' => $this->record]
                    )
                )
        ];
    }
    protected static string $view = 'filament.resources.quote-resource.pages.invoice';
}
