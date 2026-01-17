<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Quotation;
use App\Models\QuotationItem;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Mail\QuotationMail;

class QuotationBuilder extends Component
{
    // Product Definitions
    public $categories = [
        [
            'name' => 'Pantry Cupboard',
            'colors' => ['Teak Wood', 'Yellow Wood', 'White', 'Black', 'Gray', 'Natural'],
            'has_louver' => false,
            'has_key_lock' => false,
            'has_fiber_board' => true
        ],
        [
            'name' => 'Section Door',
            'colors' => ['Wood', 'White', 'Black', 'Natural'],
            'has_louver' => true,
            'has_key_lock' => true,
            'has_fiber_board' => false
        ],
        [
            'name' => 'Box Bar Bathroom Door',
            'colors' => ['Wood', 'White', 'Black', 'Natural'],
            'has_louver' => true,
            'has_key_lock' => true,
            'has_fiber_board' => false
        ],
        [
            'name' => 'Sliding Window',
            'colors' => ['Wood', 'White', 'Black', 'Natural'],
            'has_louver' => true,
            'has_key_lock' => false,
            'has_fiber_board' => false
        ],
        [
            'name' => 'Swing Window',
            'colors' => ['Wood', 'White', 'Black', 'Natural'],
            'has_louver' => true,
            'has_key_lock' => false,
            'has_fiber_board' => false
        ],
        [
            'name' => 'Casement Window',
            'colors' => ['Wood', 'White', 'Black', 'Natural'],
            'has_louver' => true,
            'has_key_lock' => false,
            'has_fiber_board' => false
        ],
        [
            'name' => 'Fix Glass',
            'colors' => ['Wood', 'White', 'Black', 'Natural'],
            'has_louver' => true,
            'has_key_lock' => false,
            'has_fiber_board' => false
        ],
        [
            'name' => 'FanLight',
            'colors' => ['Wood', 'White', 'Black', 'Natural'],
            'has_louver' => true,
            'has_key_lock' => false,
            'has_fiber_board' => false
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
        'has_fix_glass' => false,
        'has_key_lock' => false,
        'has_fiber_board' => false,
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
    public $editingQuotationId = null;

    public function mount($edit = null)
    {
        if ($edit) {
            $quotation = Quotation::with('items')->find($edit);
            if ($quotation) {
                $this->editingQuotationId = $quotation->id;
                $this->customer_name = $quotation->customer_name;
                $this->customer_phone = $quotation->customer_phone;
                $this->date = $quotation->date->format('Y-m-d');
                $this->quotation_number = $quotation->quotation_number;
                $this->fixed_charge = $quotation->fixed_charge;
                $this->transport_charge = $quotation->transport_charge;
                $this->additional_amount = $quotation->additional_amount;
                $this->additional_notes = $quotation->additional_notes;

                foreach ($quotation->items as $item) {
                    $this->items[] = [
                        'product_name' => $item->product_name,
                        'color' => $item->variant,
                        'has_louver' => $item->has_louver,
                        'has_fix_glass' => $item->has_fix_glass ?? false,
                        'has_key_lock' => $item->has_key_lock ?? false,
                        'has_fiber_board' => $item->has_fiber_board ?? false,
                        'size' => $item->size,
                        'unit_price' => $item->unit_price,
                        'quantity' => $item->quantity,
                        'total' => $item->total,
                    ];
                }
                return;
            }
        }
        $this->date = date('Y-m-d');
        $this->quotation_number = $this->generateNextNumber();
    }

    private function generateNextNumber()
    {
        $lastQuotation = Quotation::latest('id')->first();
        if (!$lastQuotation) {
            return '#QT-0001';
        }

        $lastNumber = $lastQuotation->quotation_number;
        // Extract number from format #QT-0001
        if (preg_match('/#QT-(\d+)/', $lastNumber, $matches)) {
            $nextNumber = intval($matches[1]) + 1;
            return '#QT-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
        }

        // Fallback or if format was different
        return '#QT-0001';
    }

    public function selectCategory($index)
    {
        $this->selectedCategory = $this->categories[$index];
        $this->tempItem = [
            'color' => $this->selectedCategory['colors'][0] ?? '',
            'has_louver' => false,
            'has_fix_glass' => false,
            'has_key_lock' => false,
            'has_fiber_board' => false,
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
            'has_fix_glass' => $this->tempItem['has_fix_glass'],
            'has_key_lock' => $this->tempItem['has_key_lock'],
            'has_fiber_board' => $this->tempItem['has_fiber_board'],
            'size' => $this->tempItem['size'],
            'unit_price' => $this->tempItem['unit_price'],
            'quantity' => $this->tempItem['quantity'],
            'total' => $this->tempItem['unit_price'] * $this->tempItem['quantity'],
        ];

        $this->selectedCategory = null; // Close modal/panel
    }

    public function updatedItems($value, $key)
    {
        // When items.0.quantity or items.0.unit_price changes, recalculate total
        if (preg_match('/(\d+)\.(quantity|unit_price)/', $key, $matches)) {
            $index = $matches[1];
            if (isset($this->items[$index])) {
                $qty = (float)($this->items[$index]['quantity'] ?? 1);
                $price = (float)($this->items[$index]['unit_price'] ?? 0);
                $this->items[$index]['total'] = $qty * $price;
            }
        }
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

    public function getAllColorsProperty()
    {
        $colors = [];
        foreach ($this->categories as $category) {
            $colors = array_merge($colors, $category['colors']);
        }
        return array_values(array_unique($colors));
    }

    public function saveAndGenerate()
    {
        if (empty($this->items)) {
            $this->js("alert('Please add at least one item to the quotation before generating.')");
            return;
        }

        $quotation = $this->createQuotation();
        $downloadUrl = route('quotation.download', $quotation);

        // Send email notification to configured recipient
        $this->sendEmailNotification($quotation);

        $this->resetForm();
        $this->lastQuotationNumber = $quotation->quotation_number;
        $this->lastDownloadUrl = $downloadUrl;
        
        // Use js() helper for reliable JavaScript execution
        $this->js("window.safeDownload('{$downloadUrl}', 'Quotation-{$quotation->quotation_number}.pdf')");
    }

    public function saveAndWhatsApp()
    {
        if (empty($this->items)) {
            $this->js("alert('Please add at least one item to the quotation before sharing.')");
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
        $message .= "- Valid for 1 week\n";
        $message .= "- 50% advance payment required\n";
        $message .= "- Transport calculated by location\n\n";
        $message .= "Contact: 0750944571 / 0702098959\n";
        $message .= "No.551/6 Kandy Rd, Malwatta, Nittambuwa\n\n";
        $message .= "_A detailed PDF quotation has been prepared for your reference._";

        // Use WhatsApp share URL without phone number to allow sharing with anyone
        $whatsappUrl = "https://wa.me/?text=" . urlencode($message);

        // Send email notification to configured recipient
        $this->sendEmailNotification($quotation);

        $this->resetForm();
        $this->lastQuotationNumber = $quotation->quotation_number;
        $this->lastDownloadUrl = $downloadUrl;

        // Use js() helper for reliable JavaScript execution
        $this->js("window.safeDownload('{$downloadUrl}', 'Quotation-{$quotation->quotation_number}.pdf')");
        $this->js("setTimeout(() => window.open('{$whatsappUrl}', '_blank'), 500)");
    }

    /**
     * Send email notification for quotation
     */
    private function sendEmailNotification($quotation)
    {
        $notificationEmail = config('app.notification_email', 'mohammedshn2002@gmail.com');
        
        if (empty($notificationEmail)) {
            return;
        }

        try {
            Mail::to($notificationEmail)->send(new QuotationMail($quotation));
        } catch (\Exception $e) {
            // Log error but don't stop the process
            Log::error('Failed to send quotation email: ' . $e->getMessage());
        }
    }

    private function resetForm()
    {
        // Store editing state before reset
        $wasEditing = $this->editingQuotationId !== null;
        $savedQuotationNumber = $this->quotation_number;
        
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
        
        // If we were editing, keep the same quotation number and editing ID
        // Otherwise, generate a new number
        if ($wasEditing) {
            $this->quotation_number = $savedQuotationNumber;
            // Keep editingQuotationId so subsequent saves update the same record
        } else {
            $this->quotation_number = $this->generateNextNumber();
            $this->editingQuotationId = null;
        }
        
        $this->date = date('Y-m-d');
    }

    private function createQuotation()
    {
        if ($this->editingQuotationId) {
            $quotation = Quotation::find($this->editingQuotationId);
            $quotation->update([
                'customer_name' => $this->customer_name,
                'customer_phone' => $this->customer_phone,
                'date' => $this->date,
                'subtotal' => $this->subtotal,
                'fixed_charge' => $this->fixed_charge,
                'transport_charge' => $this->transport_charge,
                'additional_amount' => $this->additional_amount,
                'additional_notes' => $this->additional_notes,
                'grand_total' => $this->total,
            ]);
            $quotation->items()->delete();
        } else {
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
        }

        foreach ($this->items as $item) {
            $quotation->items()->create([
                'product_name' => $item['product_name'],
                'variant' => $item['color'],
                'has_louver' => $item['has_louver'],
                'has_fix_glass' => $item['has_fix_glass'] ?? false,
                'has_key_lock' => $item['has_key_lock'] ?? false,
                'has_fiber_board' => $item['has_fiber_board'] ?? false,
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
