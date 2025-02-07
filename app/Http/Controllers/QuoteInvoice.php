<?php

namespace App\Http\Controllers;

use App\Models\Quote;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class QuoteInvoice extends Controller
{
    public function QuoteInvoice()
    {

        $pdf = Pdf::loadView('pdf.invoice');
        return $pdf->stream();
    }
}
