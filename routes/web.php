<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\QuoteInvoice;

// Route::get('/', function () {
//     return view('welcome');
// });
Route::get('/quote-invoice-print/{quote}', [QuoteInvoice::class, 'QuoteInvoice'])
    ->name('PRINT.INVOICE-QUOTE');
