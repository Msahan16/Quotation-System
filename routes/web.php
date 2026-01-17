<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\QuotationBuilder;
use App\Models\Quotation;
use Barryvdh\DomPDF\Facade\Pdf;

Route::get('/', QuotationBuilder::class)->name('quotation.builder');
Route::get('/edit/{edit}', QuotationBuilder::class)->name('quotation.edit');
Route::get('/quotations', \App\Livewire\QuotationList::class)->name('quotation.list');
Route::get('/quotation/{quotation}', \App\Livewire\QuotationView::class)->name('quotation.view');

Route::get('/quotation/{quotation}/download', function (Quotation $quotation) {
    $pdf = Pdf::loadView('pdf.quotation', ['quotation' => $quotation]);
    $filename = 'Quotation-' . $quotation->quotation_number . '.pdf';
    
    return response()->streamDownload(
        function () use ($pdf) {
            echo $pdf->output();
        },
        $filename,
        [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Cache-Control' => 'no-cache, no-store, must-revalidate',
            'Pragma' => 'no-cache',
            'Expires' => '0',
        ]
    );
})->name('quotation.download');
