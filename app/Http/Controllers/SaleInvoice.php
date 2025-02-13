<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use Barryvdh\DomPDF\Facade\Pdf;

class SaleInvoice extends Controller
{
    public function SaleInvoice(Sale $sale)
    {
        $pdf = Pdf::loadView(
            'pdf.sale-invoice',
            [
                'sale' => $sale->load(
                    'saleDetails.product',
                    'customer',
                    'user'
                ),
            ]
        );

        return $pdf->stream();
    }
}
