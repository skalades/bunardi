<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/admin');
});

Route::get('/orders/{order}/invoice-pdf', [\App\Http\Controllers\InvoiceController::class, 'generatePdf'])
    ->name('order.invoice.pdf')
    ->middleware('web');

Route::get('/orders/{order}/spk-pdf', [\App\Http\Controllers\InvoiceController::class, 'generateSpkPdf'])
    ->name('order.spk.pdf')
    ->middleware('web');
