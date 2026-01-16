<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Quotation;

class QuotationView extends Component
{
    public $quotation;

    public function mount(Quotation $quotation)
    {
        $this->quotation = $quotation->load('items');
    }

    public function render()
    {
        return view('livewire.quotation-view')->layout('layouts.app');
    }
}
