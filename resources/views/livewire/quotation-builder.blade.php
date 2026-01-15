<div>
    <header>
        <div>
            <h1>Professional Quotation</h1>
            <div style="font-size: 0.9rem; margin-top: 5px;">A.K.M. Aluminium Fabrication</div>
        </div>
        <div class="company-details">
            <div>No.551/6 Kandy road, Malwatta Nittambuwa</div>
            <div>0750944571 / 0702098959</div>
        </div>
    </header>

    <div class="builder-layout">
        
        <!-- Left Column: Product Selection / Item Builder -->
        <div>
            @if(!$selectedCategory)
                <h2 class="panel-title">Select Product</h2>
                <div class="product-grid">
                    @foreach($categories as $index => $cat)
                        <div class="product-card" wire:click="selectCategory({{ $index }})">
                            <div class="product-image" style="display:flex;align-items:center;justify-content:center;color:#94a3b8;">
                                <svg width="40" height="40" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                                </svg>
                            </div>
                            <div class="product-name">{{ $cat['name'] }}</div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="builder-panel">
                    <div class="panel-title">
                        <span>Configure {{ $selectedCategory['name'] }}</span>
                        <button wire:click="$set('selectedCategory', null)" class="btn btn-sm btn-danger" style="background:none;color:var(--danger);border:1px solid var(--danger);">Cancel</button>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Color Variant</label>
                        <div class="color-options">
                            @foreach($selectedCategory['colors'] as $color)
                                <div 
                                    class="color-option {{ $tempItem['color'] === $color ? 'selected' : '' }}" 
                                    data-color="{{ $color }}"
                                    wire:click="$set('tempItem.color', '{{ $color }}')"
                                    title="{{ $color }}"
                                >
                                    @if($tempItem['color'] === $color) 
                                        <div style="position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);color:{{ in_array($color, ['White', 'Yellow Wood', 'Natural']) ? 'black' : 'white' }};">✓</div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                        <div style="margin-top:5px;font-size:0.8rem;color:var(--text-secondary);">Selected: {{ $tempItem['color'] }}</div>
                    </div>

                    @if($selectedCategory['has_louver'])
                    <div class="form-group">
                        <label class="form-label">Louver Option</label>
                        <label class="switch">
                            <input type="checkbox" wire:model.live="tempItem.has_louver">
                            <span class="slider"></span>
                        </label>
                        <span style="margin-left: 10px; font-size: 0.9rem;">{{ $tempItem['has_louver'] ? 'With Louver' : 'Standard' }}</span>
                    </div>
                    @endif

                    <div class="form-group">
                        <label class="form-label">Size / Dimensions</label>
                        <input type="text" class="form-control" wire:model="tempItem.size" placeholder="e.g. 10x12 or Standard">
                        @error('tempItem.size') <span style="color:red;font-size:0.8rem;">{{ $message }}</span> @enderror
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                        <div class="form-group">
                            <label class="form-label">Unit Price (Rs)</label>
                            <input type="number" class="form-control" wire:model.live="tempItem.unit_price" step="0.01">
                            @error('tempItem.unit_price') <span style="color:red;font-size:0.8rem;">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group">
                            <label class="form-label">Quantity</label>
                            <input type="number" class="form-control" wire:model.live="tempItem.quantity" min="1">
                        </div>
                    </div>

                    <div style="background:var(--bg);padding:15px;border-radius:10px;margin-bottom:20px;text-align:right;">
                        <div style="font-size:0.9rem;color:var(--text-secondary);">Line Total</div>
                        <div style="font-size:1.5rem;font-weight:700;color:var(--primary);">
                            Rs {{ number_format((float)$tempItem['unit_price'] * (int)$tempItem['quantity'], 2) }}
                        </div>
                    </div>

                    <button wire:click="addItem" class="btn btn-primary" style="width:100%;">Add to Quotation</button>
                </div>
            @endif
        </div>

        <!-- Right Column: Summary -->
        <div>
            <div class="builder-panel">
                <h3 class="panel-title">Quotation Details</h3>
                
                <div class="form-group">
                    <label class="form-label">Customer Name</label>
                    <input type="text" class="form-control" wire:model="customer_name" placeholder="Enter Customer Name">
                    @error('customer_name') <span style="color:red;font-size:0.8rem;">{{ $message }}</span> @enderror
                </div>
                
                <div class="form-group">
                    <label class="form-label">Phone Number</label>
                    <input type="text" class="form-control" wire:model="customer_phone" placeholder="07...">
                </div>

                <div style="border-top: 1px solid var(--border); margin: 20px 0;"></div>

                <div style="max-height: 400px; overflow-y: auto;">
                    @if(count($items) > 0)
                        <table class="quote-table">
                            <thead>
                                <tr>
                                    <th>Item</th>
                                    <th>Total</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($items as $index => $item)
                                    <tr>
                                        <td>
                                            <div style="font-weight:600;">{{ $item['product_name'] }}</div>
                                            <div style="font-size:0.8rem;color:var(--text-secondary);">
                                                {{ $item['size'] }} | {{ $item['color'] }} 
                                                @if($item['has_louver']) | Louver @endif
                                                <br>
                                                {{ $item['quantity'] }} x {{ $item['unit_price'] }}
                                            </div>
                                        </td>
                                        <td style="vertical-align:top;font-weight:600;">
                                            {{ number_format($item['total'], 2) }}
                                        </td>
                                        <td style="vertical-align:top;text-align:right;">
                                            <button wire:click="removeItem({{ $index }})" style="border:none;background:none;color:var(--danger);cursor:pointer;">×</button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <div style="text-align:center;padding:20px;color:var(--text-secondary);">
                            No items added. Select a product to begin.
                        </div>
                    @endif
                </div>

                <div class="totals-section">
                    <div class="total-row">
                        <span>Subtotal</span>
                        <span>{{ number_format($this->subtotal, 2) }}</span>
                    </div>
                    
                    <div class="form-group" style="margin-bottom:8px; display:flex; justify-content:space-between; align-items:center;">
                        <span style="font-size:0.9rem; color:var(--text-secondary);">Fixed Charge</span>
                        <input type="number" step="0.01" class="form-control" wire:model.live="fixed_charge" placeholder="0.00" style="font-size:0.9rem; padding:6px; width:140px; text-align:right;">
                    </div>
                    <div class="form-group" style="margin-bottom:8px; display:flex; justify-content:space-between; align-items:center;">
                        <span style="font-size:0.9rem; color:var(--text-secondary);">Transport Charge</span>
                        <input type="number" step="0.01" class="form-control" wire:model.live="transport_charge" placeholder="0.00" style="font-size:0.9rem; padding:6px; width:140px; text-align:right;">
                    </div>
                    <div class="form-group" style="margin-bottom:8px; display:flex; justify-content:space-between; align-items:center;">
                        <span style="font-size:0.9rem; color:var(--text-secondary);">Additional Amount</span>
                        <input type="number" step="0.01" class="form-control" wire:model.live="additional_amount" placeholder="0.00" style="font-size:0.9rem; padding:6px; width:140px; text-align:right;">
                    </div>

                    <div class="total-row grand-total">
                        <span>Grand Total</span>
                        <span>Rs {{ number_format($this->total, 2) }}</span>
                    </div>

                    <div class="form-group" style="margin-top:15px;">
                        <textarea class="form-control" wire:model="additional_notes" placeholder="Additional Notes..." rows="2"></textarea>
                    </div>
                </div>

                <div style="margin-top:20px; display:flex; gap:10px; flex-direction:column;">
                    <button wire:click="saveAndGenerate" class="btn btn-primary" wire:loading.attr="disabled">
                        <span wire:loading.remove>Generate & Download PDF</span>
                        <span wire:loading>Processing...</span>
                    </button>
                    
                    <button wire:click="saveAndWhatsApp" class="btn" style="background:#25D366;color:white;display:flex;align-items:center;justify-content:center;" wire:loading.attr="disabled">
                        <svg style="width:20px;height:20px;margin-right:8px;" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>
                        <span wire:loading.remove>Generate & WhatsApp</span>
                        <span wire:loading>Sharing...</span>
                    </button>
                </div>

                @if($lastQuotationNumber)
                    <div style="margin-top:20px; padding:15px; background:#f0f9ff; border:1px solid #bae6fd; border-radius:10px; text-align:center;">
                        <div style="color:#0369a1; font-weight:700; margin-bottom:5px;">
                            ✓ Quotation #{{ $lastQuotationNumber }} Created!
                        </div>
                        <div style="font-size:0.8rem; color:#64748b; margin-bottom:10px;">The PDF has been downloaded automatically.</div>
                        <div style="display:flex; flex-direction:column; gap:8px;">
                            <a href="{{ $lastDownloadUrl }}" class="btn btn-sm" style="background:#0284c7; color:white; text-decoration:none;">
                                Re-download PDF
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('livewire:initialized', () => {
            @this.on('quotation-created', (event) => {
                const data = event[0];
                
                // 1. Trigger Download
                const link = document.createElement('a');
                link.href = data.downloadUrl;
                link.download = `Quotation-${data.quotationNumber}.pdf`;
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);

                // 2. Open WhatsApp if URL exists
                if (data.whatsappUrl) {
                    setTimeout(() => {
                        window.open(data.whatsappUrl, '_blank');
                    }, 800);
                }
            });

            @this.on('show-error', (event) => {
                const data = event[0];
                Swal.fire({
                    icon: 'warning',
                    title: 'Oops!',
                    text: data.message,
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#0f172a',
                    customClass: {
                        popup: 'swal-custom'
                    }
                });
            });
        });
    </script>
</div>
