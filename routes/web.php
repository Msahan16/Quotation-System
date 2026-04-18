<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\QuotationBuilder;
use App\Models\Quotation;
use Barryvdh\DomPDF\Facade\Pdf;

Route::get('/pdf-test', function () {
    $pdf = Pdf::loadView('pdf.test');
    return $pdf->download('test.pdf');
});


Route::get('/', QuotationBuilder::class)->name('quotation.builder');
Route::get('/edit/{edit}', QuotationBuilder::class)->name('quotation.edit');
Route::get('/quotations', \App\Livewire\QuotationList::class)->name('quotation.list');
Route::get('/quotation/{quotation}', \App\Livewire\QuotationView::class)->name('quotation.view');

Route::get('/quotation/{quotation}/download', function (Quotation $quotation) {
    try {
        // Set longer execution time for PDF generation
        set_time_limit(120);
        ini_set('max_execution_time', 120);
        
        // Load quotation items
        $quotation->load('items');
        
        // Generate PDF with optimized settings
        $pdf = Pdf::loadView('pdf.quotation', ['quotation' => $quotation])
            ->setPaper('a4', 'portrait')
            ->setOption('isHtml5ParserEnabled', true)
            ->setOption('isRemoteEnabled', true)
            ->setOption('defaultFont', 'sans-serif');
        
        $filename = 'Quotation-' . $quotation->quotation_number . '.pdf';
        
        // Return with proper headers for CORS and caching
        return response($pdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Access-Control-Allow-Origin' => '*',
            'Access-Control-Allow-Methods' => 'GET, OPTIONS',
            'Access-Control-Allow-Headers' => 'Content-Type, Accept',
            'Cache-Control' => 'no-cache, no-store, must-revalidate',
            'Pragma' => 'no-cache',
            'Expires' => '0',
        ]);
    } catch (\Exception $e) {
        \Log::error('PDF Download Error: ' . $e->getMessage(), [
            'quotation_id' => $quotation->id,
            'trace' => $e->getTraceAsString()
        ]);
        
        // Return error response with CORS headers
        return response()->json([
            'error' => 'Failed to generate PDF',
            'message' => config('app.debug') ? $e->getMessage() : 'Please try again or contact support.'
        ], 500, [
            'Access-Control-Allow-Origin' => '*',
            'Content-Type' => 'application/json'
        ]);
    }
})->name('quotation.download');
