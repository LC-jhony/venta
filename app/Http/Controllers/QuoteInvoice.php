<?php

namespace App\Http\Controllers;

use App\Models\Quote;
use App\Models\Setting;
use Barryvdh\DomPDF\Facade\Pdf;

class QuoteInvoice extends Controller
{
    public function QuoteInvoice(Quote $quote)
    {
        $setting = Setting::first();
        $pdf = Pdf::loadView(
            'pdf.invoice',
            [
                'quote' => $quote->load(
                    'detailQuote.product',
                    'user',
                    'supplier'
                ),
                'setting' => $setting,
            ]
        );

        return $pdf->stream();
    }
}
