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
    try {
        $quotation->load('items');
        $pdf = Pdf::loadView('pdf.quotation', ['quotation' => $quotation]);
        $filename = 'Quotation-' . $quotation->quotation_number . '.pdf';
        
        return $pdf->download($filename);
    } catch (\Exception $e) {
        \Log::error('PDF Download Error: ' . $e->getMessage());
        return response()->json(['error' => 'Failed to generate PDF'], 500);
    }
})->name('quotation.download');
