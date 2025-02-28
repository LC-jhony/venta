<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Setting;
use Barryvdh\DomPDF\Facade\Pdf;

class SaleInvoice extends Controller
{
    public function SaleInvoice(Sale $sale)
    {
        $setting = Setting::first();
        $pdf = Pdf::loadView(
            'pdf.sale-invoice',
            [
                'sale' => $sale->load(
                    'saleDetails.product',
                    'customer',
                    'user'
                ),
                'setting' => $setting,
            ]
        );

        return $pdf->stream();
    }
}
