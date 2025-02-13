<?php

namespace App\Http\Controllers;

use App\Models\Quote;
use Barryvdh\DomPDF\Facade\Pdf;

class QuoteInvoice extends Controller
{
    public function QuoteInvoice(Quote $quote)
    {
        // dd($quote);
        $pdf = Pdf::loadView(
            'pdf.invoice',
            [
                'quote' => $quote->load(
                    'detailQuote.product',
                    'user',
                    'supplier'
                ),

            ]
        );

        return $pdf->stream();
    }
}
