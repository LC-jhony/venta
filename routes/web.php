<?php

use App\Http\Controllers\QuoteInvoice;
use App\Http\Controllers\QuoteInvoiceController;
use App\Http\Controllers\SaleInvoice;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/quote-invoice/{quote}', QuoteInvoiceController::class)
    ->name('QUOTE-INVOICE');

// Route::get('/quote-invoice-print/{quote}', [QuoteInvoice::class, 'QuoteInvoice'])
//     ->name('PRINT.INVOICE-QUOTE');
Route::get('/sale-invoice-print/{sale}', [SaleInvoice::class, 'SaleInvoice'])
    ->name('PRINT.INVOICE-SALE');
