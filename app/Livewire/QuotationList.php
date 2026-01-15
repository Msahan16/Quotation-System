<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Quotation;

use Livewire\Attributes\Url;
use Illuminate\Support\Str;

class QuotationList extends Component
{
    use WithPagination;

    #[Url]
    public $search = '';

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
        $message .= "*Quotation #" . $quotation->quotation_number . "*\n";
        $message .= "Date: " . $quotation->date->format('d M Y') . "\n";
        $message .= "━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";
        
        $message .= "*ITEMS:*\n\n";
        
        foreach ($quotation->items as $index => $item) {
            $itemNum = $index + 1;
            $message .= "{$itemNum}. *{$item->product_name}*\n";
            $message .= "   • Size: {$item->size}\n";
            $message .= "   • Color: {$item->variant}\n";
            if ($item->has_louver) {
                $message .= "   • With Louver\n";
            }
            $message .= "   • Qty: {$item->quantity} × Rs. " . number_format($item->unit_price, 2) . "\n";
            $message .= "   • Total: *Rs. " . number_format($item->total, 2) . "*\n\n";
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
            $message .= "📝 *Note:* {$quotation->additional_notes}\n\n";
        }
        
        $message .= "━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";
        $message .= "*Terms & Conditions:*\n";
        $message .= "• Valid for 1 week\n\n";
        $message .= "Contact: 0750944571 / 0702098959\n";
        $message .= "No.551/6 Kandy Rd, Malwatta, Nittambuwa\n\n";
        $message .= "_A detailed PDF quotation has been prepared for your reference._";

        $phone = $quotation->customer_phone ? preg_replace('/[^0-9]/', '', '94' . ltrim($quotation->customer_phone, '0')) : '';
        $whatsappUrl = "https://wa.me/{$phone}?text=" . urlencode($message);

        $this->dispatch('open-whatsapp', ['url' => $whatsappUrl]);
    }

    public function render()
    {
        $quotations = Quotation::where('quotation_number', 'like', '%' . $this->search . '%')
            ->orWhere('customer_name', 'like', '%' . $this->search . '%')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.quotation-list', [
            'quotations' => $quotations
        ])->layout('layouts.app');
    }
}
