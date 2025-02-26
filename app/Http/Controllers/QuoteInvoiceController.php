<?php

namespace App\Http\Controllers;

use App\Models\Quote;
use App\Models\Setting;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class QuoteInvoiceController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Quote $quote)
    {
        $setting = Setting::first();
        $pdf = Pdf::loadView(
            'pdf.invoice',
            [
                'quote' => $quote->load(
                    'quoteProducts.product',
                    'user',
                    'suppliers'
                ),
                'setting' => $setting
            ]
        );

        return $pdf->stream();
    }
}
