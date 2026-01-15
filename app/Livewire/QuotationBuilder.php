<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Quotation;
use App\Models\QuotationItem;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;

class QuotationBuilder extends Component
{
    // Product Definitions
    public $categories = [
        [
            'name' => 'Pantry Cupboard',
            'colors' => ['Teak Wood', 'Yellow Wood', 'White', 'Black', 'Gray', 'Natural'],
            'has_louver' => false
        ],
        [
            'name' => 'Section Door',
            'colors' => ['Wood', 'White', 'Black', 'Natural'],
            'has_louver' => true
        ],
        [
            'name' => 'Box Bar Bathroom Door',
            'colors' => ['Wood', 'White', 'Black', 'Natural'],
            'has_louver' => true
        ],
        [
            'name' => 'Sliding Window',
            'colors' => ['Wood', 'White', 'Black', 'Natural'],
            'has_louver' => true
        ],
        [
            'name' => 'Swing Window',
            'colors' => ['Wood', 'White', 'Black', 'Natural'],
            'has_louver' => true
        ],
        [
            'name' => 'Fix Glass',
            'colors' => ['Wood', 'White', 'Black', 'Natural'],
            'has_louver' => true
        ],
        [
            'name' => 'FanLight',
            'colors' => ['Wood', 'White', 'Black', 'Natural'],
            'has_louver' => true
        ],
    ];

    // Form inputs
    public $customer_name = '';
    public $customer_phone = '';
    public $date;
    public $quotation_number;
    
    // Current Item building state
    public $selectedCategory = null;
    public $tempItem = [
        'color' => '',
        'has_louver' => false,
        'size' => '',
        'unit_price' => 0,
        'quantity' => 1,
    ];

    public $items = [];

    // Totals
    public $fixed_charge = 0;
    public $transport_charge = 0;
    public $additional_amount = 0;
    public $additional_notes = '';

    public $lastQuotationNumber = null;
    public $lastDownloadUrl = null;

    public function mount()
    {
        $this->date = date('Y-m-d');
        $this->quotation_number = 'QT-' . strtoupper(Str::random(6));
    }

    public function selectCategory($index)
    {
        $this->selectedCategory = $this->categories[$index];
        $this->tempItem = [
            'color' => $this->selectedCategory['colors'][0] ?? '',
            'has_louver' => false,
            'size' => '',
            'unit_price' => 0,
            'quantity' => 1,
        ];
    }

    public function addItem()
    {
        $this->validate([
            'tempItem.size' => 'required',
            'tempItem.unit_price' => 'required|numeric|min:0',
            'tempItem.quantity' => 'required|integer|min:1',
        ]);

        $this->items[] = [
            'product_name' => $this->selectedCategory['name'],
            'color' => $this->tempItem['color'],
            'has_louver' => $this->tempItem['has_louver'],
            'size' => $this->tempItem['size'],
            'unit_price' => $this->tempItem['unit_price'],
            'quantity' => $this->tempItem['quantity'],
            'total' => $this->tempItem['unit_price'] * $this->tempItem['quantity'],
        ];

        $this->selectedCategory = null; // Close modal/panel
    }

    public function removeItem($index)
    {
        unset($this->items[$index]);
        $this->items = array_values($this->items);
    }

    public function getTotalProperty()
    {
        $subtotal = collect($this->items)->sum('total');
        return $subtotal + (float)$this->fixed_charge + (float)$this->transport_charge + (float)$this->additional_amount;
    }

    public function getSubtotalProperty()
    {
        return collect($this->items)->sum('total');
    }

    public function saveAndGenerate()
    {
        if (empty($this->items)) {
            $this->dispatch('show-error', ['message' => 'Please add at least one item to the quotation before generating.']);
            return;
        }

        $quotation = $this->createQuotation();
        $downloadUrl = route('quotation.download', $quotation);

        $this->resetForm();
        $this->lastQuotationNumber = $quotation->quotation_number;
        $this->lastDownloadUrl = $downloadUrl;
        
        $this->dispatch('quotation-created', [
            'downloadUrl' => $downloadUrl,
            'quotationNumber' => $quotation->quotation_number
        ]);
    }

    public function saveAndWhatsApp()
    {
        if (empty($this->items)) {
            $this->dispatch('show-error', ['message' => 'Please add at least one item to the quotation before sharing.']);
            return;
        }

        $quotation = $this->createQuotation();
        $downloadUrl = route('quotation.download', $quotation);

        // Build detailed WhatsApp message
        $customerName = $this->customer_name ?: 'Customer';
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
        $message .= "✅ *Terms & Conditions:*\n";
        $message .= "• Valid for 1 week\n";
        $message .= "• 50% advance payment required\n";
        $message .= "• Transport calculated by location\n\n";
        $message .= "📞 Contact: 0750944571 / 0702098959\n";
        $message .= "📍 No.551/6 Kandy Rd, Malwatta, Nittambuwa\n\n";
        $message .= "_A detailed PDF quotation has been prepared for your reference._";

        $phone = $this->customer_phone ? preg_replace('/[^0-9]/', '', '94' . ltrim($this->customer_phone, '0')) : '';
        $whatsappUrl = "https://wa.me/{$phone}?text=" . urlencode($message);

        $this->resetForm();
        $this->lastQuotationNumber = $quotation->quotation_number;
        $this->lastDownloadUrl = $downloadUrl;

        $this->dispatch('quotation-created', [
            'downloadUrl' => $downloadUrl,
            'whatsappUrl' => $whatsappUrl,
            'quotationNumber' => $quotation->quotation_number
        ]);
    }

    private function resetForm()
    {
        $this->customer_name = '';
        $this->customer_phone = '';
        $this->items = [];
        $this->fixed_charge = 0;
        $this->transport_charge = 0;
        $this->additional_amount = 0;
        $this->additional_notes = '';
        $this->selectedCategory = null;
        $this->lastQuotationNumber = null;
        $this->lastDownloadUrl = null;
        $this->quotation_number = 'QT-' . strtoupper(Str::random(6));
        $this->date = date('Y-m-d');
    }

    private function createQuotation()
    {
        $quotation = Quotation::create([
            'customer_name' => $this->customer_name,
            'customer_phone' => $this->customer_phone,
            'date' => $this->date,
            'quotation_number' => $this->quotation_number,
            'subtotal' => $this->subtotal,
            'fixed_charge' => $this->fixed_charge,
            'transport_charge' => $this->transport_charge,
            'additional_amount' => $this->additional_amount,
            'additional_notes' => $this->additional_notes,
            'grand_total' => $this->total,
        ]);

        foreach ($this->items as $item) {
            $quotation->items()->create([
                'product_name' => $item['product_name'],
                'variant' => $item['color'],
                'has_louver' => $item['has_louver'],
                'size' => $item['size'],
                'unit_price' => $item['unit_price'],
                'quantity' => $item['quantity'],
                'total' => $item['total'],
            ]);
        }

        return $quotation;
    }

    public function render()
    {
        return view('livewire.quotation-builder')->layout('layouts.app');
    }
}
