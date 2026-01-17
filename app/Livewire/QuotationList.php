<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Quotation;
use Livewire\Attributes\Url;

class QuotationList extends Component
{
    use WithPagination;

    #[Url(history: true)]
    public $search = '';

    // Reset pagination when search changes
    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function deleteQuotation($id)
    {
        $quotation = Quotation::find($id);
        if ($quotation) {
            $quotation->items()->delete();
            $quotation->delete();
            session()->flash('message', 'Quotation deleted successfully.');
        }
    }

    public function shareToWhatsApp($id)
    {
        $quotation = Quotation::with('items')->find($id);
        if (!$quotation) return;

        $customerName = $quotation->customer_name ?: 'Customer';
        $message = "*AKM ALUMINIUM FABRICATION*\n";
        $message .= "━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";
        $message .= "Dear *{$customerName}*,\n\n";
        $message .= "Thank you for your interest! Here is your quotation:\n\n";
        $message .= "*Quotation " . $quotation->quotation_number . "*\n";
        $message .= "Date: " . $quotation->date->format('d M Y') . "\n";
        $message .= "━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";
        
        $message .= "*ITEMS:*\n\n";
        
        foreach ($quotation->items as $index => $item) {
            $itemNum = $index + 1;
            $message .= "{$itemNum}. *{$item->product_name}*\n";
            $message .= "   - Size: {$item->size}\n";
            $message .= "   - Color: {$item->variant}\n";
            if ($item->has_louver) {
                $message .= "   - With Louver\n";
            }
            if ($item->has_fix_glass) {
                $message .= "   - With Fix Glass\n";
            }
            if ($item->has_key_lock) {
                $message .= "   - With Key Lock\n";
            }
            if ($item->has_fiber_board) {
                $message .= "   - With Fiber Board\n";
            }
            $message .= "   - Qty: {$item->quantity} x Rs. " . number_format($item->unit_price, 2) . "\n";
            $message .= "   - Total: *Rs. " . number_format($item->total, 2) . "*\n\n";
        }
        
        $message .= "━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";
        $message .= "*SUMMARY:*\n";
        $message .= "Subtotal: Rs. " . number_format($quotation->subtotal, 2) . "\n";
        
        if ($quotation->fixed_charge > 0) {
            $message .= "Fixed Charge: Rs. " . number_format($quotation->fixed_charge, 2) . "\n";
        }
        if ($quotation->transport_charge > 0) {
            $message .= "Transport: Rs. " . number_format($quotation->transport_charge, 2) . "\n";
        }
        if ($quotation->additional_amount > 0) {
            $message .= "Additional: Rs. " . number_format($quotation->additional_amount, 2) . "\n";
        }
        
        $message .= "\n*GRAND TOTAL: Rs. " . number_format($quotation->grand_total, 2) . "*\n\n";
        
        if ($quotation->additional_notes) {
            $message .= "Note: {$quotation->additional_notes}\n\n";
        }
        
        $message .= "━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";
        $message .= "*Terms & Conditions:*\n";
        $message .= "- Valid for 1 week\n\n";
        $message .= "Contact: 0750944571 / 0702098959\n";
        $message .= "No.551/6 Kandy Rd, Malwatta, Nittambuwa\n\n";
        $message .= "_A detailed PDF quotation has been prepared for your reference._";

        // Use WhatsApp share URL without phone number to allow sharing with anyone
        $whatsappUrl = "https://wa.me/?text=" . urlencode($message);

        // Use js() helper to directly open WhatsApp - more reliable than dispatch
        $this->js("window.open('{$whatsappUrl}', '_blank')");
    }

    public function render()
    {
        $searchTerm = '%' . $this->search . '%';
        
        $quotations = Quotation::where(function($query) use ($searchTerm) {
                $query->where('quotation_number', 'like', $searchTerm)
                      ->orWhere('customer_name', 'like', $searchTerm);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.quotation-list', [
            'quotations' => $quotations
        ])->layout('layouts.app');
    }
}
