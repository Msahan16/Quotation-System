<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\QuotationBuilder;
use App\Models\Quotation;
use Barryvdh\DomPDF\Facade\Pdf;

Route::get('/', QuotationBuilder::class)->name('quotation.builder');

Route::get('/quotation/{quotation}/download', function (Quotation $quotation) {
    return response()->streamDownload(function () use ($quotation) {
        echo Pdf::loadView('pdf.quotation', ['quotation' => $quotation])->output();
    }, 'Quotation-' . $quotation->quotation_number . '.pdf');
})->name('quotation.download');
