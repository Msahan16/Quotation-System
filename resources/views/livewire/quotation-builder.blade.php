<div>
    <header class="builder-header">
        <div class="header-left">
            <h1>{{ $editingQuotationId ? 'Edit Quotation' : 'Professional Quotation' }}</h1>
            <div class="subtitle">A.K.M. Aluminium Fabrication</div>
        </div>
        <div class="header-right">
            <div class="company-details">
                <div>No.551/6 Kandy road, Malwatta Nittambuwa</div>
                <div>0750944571 / 0702098959</div>
            </div>
            <a href="{{ route('quotation.list') }}" wire:navigate class="btn view-history-btn">
                <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>View History</span>
            </a>
        </div>
    </header>

    <div class="builder-layout">
        
        <!-- Left Column: Product Selection / Item Builder -->
        <div>
            @if(!$selectedCategory)
                <h2 class="panel-title">Select Product</h2>
                <div class="product-grid">
                    @foreach($categories as $index => $cat)
                        @php
                            $imageMap = [
                                'Pantry Cupboard' => 'Pantry.png',
                                'Section Door' => 'section Door.png',
                                'Box Bar Bathroom Door' => 'BoxBarDoor.png',
                                'Sliding Window' => 'Sliding.png',
                                'Swing Window' => 'swing window.png',
                                'Casement Window' => 'casement.png',
                                'Fix Glass' => 'Fix Glass.png',
                                'FanLight' => 'FanLight.png'
                            ];
                            $imageName = $imageMap[$cat['name']] ?? 'Pantry.png';
                        @endphp
                        <div class="product-card" wire:click="selectCategory({{ $index }})">
                            <div class="product-image">
                                <img src="{{ asset($imageName) }}" alt="{{ $cat['name'] }}" style="width: 100%; height: 100%; object-fit: contain;">
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

                    @if(!$selectedCategory['has_fiber_board'] && $selectedCategory['name'] !== 'Fix Glass')
                    <div class="form-group">
                        <label class="form-label">Fix Glass Option</label>
                        <label class="switch">
                            <input type="checkbox" wire:model.live="tempItem.has_fix_glass">
                            <span class="slider"></span>
                        </label>
                        <span style="margin-left: 10px; font-size: 0.9rem;">{{ $tempItem['has_fix_glass'] ? 'With Fix Glass' : 'Standard' }}</span>
                    </div>
                    @endif

                    @if($selectedCategory['has_fiber_board'])
                    <div class="form-group">
                        <label class="form-label">Fiber Board Option</label>
                        <label class="switch">
                            <input type="checkbox" wire:model.live="tempItem.has_fiber_board">
                            <span class="slider"></span>
                        </label>
                        <span style="margin-left: 10px; font-size: 0.9rem;">{{ $tempItem['has_fiber_board'] ? 'With Fiber Board' : 'Standard' }}</span>
                    </div>
                    @endif

                    @if($selectedCategory['has_key_lock'])
                    <div class="form-group">
                        <label class="form-label">Key Lock Option</label>
                        <label class="switch">
                            <input type="checkbox" wire:model.live="tempItem.has_key_lock">
                            <span class="slider"></span>
                        </label>
                        <span style="margin-left: 10px; font-size: 0.9rem;">{{ $tempItem['has_key_lock'] ? 'With Key Lock' : 'Standard' }}</span>
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

                <div class="items-container">
                    @if(count($items) > 0)
                        <div class="items-list">
                            @foreach($items as $index => $item)
                                <div class="item-card">
                                    <div class="item-header">
                                        <div class="item-info">
                                            <div class="item-name">{{ $item['product_name'] }}</div>
                                            <div class="item-details-editable">
                                                <div class="editable-field-group">
                                                    <input type="text" wire:model.live="items.{{ $index }}.size" class="size-input" placeholder="Size">
                                                    <span class="separator">|</span>
                                                    <select wire:model.live="items.{{ $index }}.color" class="color-select">
                                                        @foreach($this->allColors as $color)
                                                            <option value="{{ $color }}">{{ $color }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="badges-row">
                                                    @if($item['has_louver']) <span class="louver-badge">+ Louver</span> @endif
                                                    @if($item['has_fix_glass'] ?? false) <span class="fix-glass-badge">+ Fix Glass</span> @endif
                                                    @if($item['has_key_lock'] ?? false) <span class="key-lock-badge">+ Key Lock</span> @endif
                                                    @if($item['has_fiber_board'] ?? false) <span class="fiber-board-badge">+ Fiber Board</span> @endif
                                                </div>
                                            </div>
                                        </div>
                                        <button wire:click="removeItem({{ $index }})" class="remove-btn" title="Remove">
                                            <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                        </button>
                                    </div>
                                    <div class="item-inputs">
                                        <div class="input-group">
                                            <label>Qty</label>
                                            <input type="number" wire:model.live="items.{{ $index }}.quantity" min="1" class="qty-input">
                                        </div>
                                        <div class="input-group">
                                            <label>Unit Price</label>
                                            <input type="number" wire:model.live="items.{{ $index }}.unit_price" min="0" step="0.01" class="price-input">
                                        </div>
                                        <div class="input-group total-display">
                                            <label>Total</label>
                                            <div class="total-value">Rs. {{ number_format($item['total'] ?? 0, 2) }}</div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
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
                    <button type="button" wire:click="saveAndGenerate" class="btn btn-primary" wire:loading.attr="disabled" wire:target="saveAndGenerate">
                        <span wire:loading.remove wire:target="saveAndGenerate">Generate & Download PDF</span>
                        <span wire:loading wire:target="saveAndGenerate">Processing...</span>
                    </button>
                    
                    <button type="button" wire:click="saveAndWhatsApp" class="btn" style="background:#25D366;color:white;display:flex;align-items:center;justify-content:center;" wire:loading.attr="disabled" wire:target="saveAndWhatsApp">
                        <svg style="width:20px;height:20px;margin-right:8px;" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>
                        <span wire:loading.remove wire:target="saveAndWhatsApp">Generate & WhatsApp</span>
                        <span wire:loading wire:target="saveAndWhatsApp">Sharing...</span>
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
        // Initialize event listeners immediately when DOM is ready
        document.addEventListener('DOMContentLoaded', function() {
            // Wait for Livewire to be available
            if (typeof Livewire !== 'undefined') {
                setupEventListeners();
            } else {
                // If Livewire isn't ready yet, wait for it
                document.addEventListener('livewire:initialized', setupEventListeners);
            }
        });

        function setupEventListeners() {
            // Handle quotation creation
            Livewire.on('quotation-created', (event) => {
                try {
                    const data = Array.isArray(event) ? event[0] : event;
                    
                    console.log('Quotation created event received:', data);
                    
                    // 1. Trigger Download
                    if (data.downloadUrl) {
                        const link = document.createElement('a');
                        link.href = data.downloadUrl;
                        link.download = `Quotation-${data.quotationNumber}.pdf`;
                        link.style.display = 'none';
                        document.body.appendChild(link);
                        link.click();
                        
                        // Clean up after a short delay
                        setTimeout(() => {
                            document.body.removeChild(link);
                        }, 100);
                    }

                    // 2. Open WhatsApp if URL exists
                    if (data.whatsappUrl) {
                        setTimeout(() => {
                            window.open(data.whatsappUrl, '_blank');
                        }, 500);
                    }

                    // 3. Force component refresh to clear all fields
                    setTimeout(() => {
                        if (window.Livewire) {
                            window.Livewire.find(document.querySelector('[wire\\\\:id]').getAttribute('wire:id')).$refresh();
                        }
                    }, 1000);
                } catch (error) {
                    console.error('Error handling quotation-created event:', error);
                }
            });

            // Handle error messages
            Livewire.on('show-error', (event) => {
                try {
                    const data = Array.isArray(event) ? event[0] : event;
                    
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Oops!',
                            text: data.message || 'An error occurred',
                            confirmButtonText: 'OK',
                            confirmButtonColor: '#0f172a',
                            customClass: {
                                popup: 'swal-custom'
                            }
                        });
                    } else {
                        alert(data.message || 'An error occurred');
                    }
                } catch (error) {
                    console.error('Error handling show-error event:', error);
                }
            });
        }
    </script>
    <style>
        .builder-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: var(--primary);
            color: white;
            padding: 20px;
            border-radius: 0 0 20px 20px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
        }
        .builder-header h1 {
            margin: 0;
            font-size: 1.5rem;
            font-weight: 600;
        }
        .builder-header .subtitle {
            font-size: 0.9rem;
            margin-top: 5px;
            opacity: 0.9;
        }
        .header-right {
            display: flex; 
            align-items: center; 
            gap: 20px;
        }
        .header-right .company-details {
            text-align: right;
            font-size: 0.85rem;
            opacity: 0.9;
            line-height: 1.4;
        }
        .view-history-btn {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            color: white;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 600;
            padding: 12px 28px;
            border-radius: 99px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            margin-top: 5px;
        }
        .view-history-btn:hover {
            background: rgba(255, 255, 255, 0.2);
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
            border-color: rgba(255, 255, 255, 0.4);
        }
        .view-history-btn:active {
            transform: translateY(0);
        }
        .view-history-btn svg {
            transition: transform 0.3s;
        }
        .view-history-btn:hover svg {
            transform: rotate(-15deg);
        }

        /* Items Container Styles */
        .items-container {
            max-height: 500px;
            overflow-y: auto;
            padding: 5px;
        }
        .items-list {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }
        .item-card {
            background: #f8fafc;
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 15px;
            transition: all 0.2s;
        }
        .item-card:hover {
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
            border-color: #cbd5e1;
        }
        .item-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 12px;
        }
        .item-info {
            flex: 1;
        }
        .item-name {
            font-weight: 600;
            font-size: 0.95rem;
            color: var(--text-primary);
            margin-bottom: 4px;
        }
        .item-details {
            font-size: 0.8rem;
            color: var(--text-secondary);
        }
        .item-details-editable {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }
        .editable-field-group {
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .size-input, .color-select {
            padding: 4px 8px;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            font-size: 0.8rem;
            color: var(--text-secondary);
            background: white;
            outline: none;
            transition: all 0.2s;
            font-weight: 500;
        }
        .size-input {
            width: 80px;
        }
        .color-select {
            flex: 1;
            min-width: 100px;
            cursor: pointer;
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%23475569' d='M6 9L1 4h10z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 8px center;
            padding-right: 28px;
        }
        .size-input:focus, .color-select:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
            color: var(--text-primary);
        }
        .separator {
            color: var(--text-secondary);
            font-weight: 300;
            font-size: 0.9rem;
        }
        .badges-row {
            display: flex;
            flex-wrap: wrap;
            gap: 4px;
        }
        .louver-badge {
            background: #dbeafe;
            color: #1e40af;
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 0.75rem;
            font-weight: 600;
        }
        .fix-glass-badge {
            background: #d1fae5;
            color: #065f46;
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 0.75rem;
            font-weight: 600;
            margin-left: 4px;
        }
        .key-lock-badge {
            background: #fed7aa;
            color: #92400e;
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 0.75rem;
            font-weight: 600;
            margin-left: 4px;
        }
        .fiber-board-badge {
            background: #e9d5ff;
            color: #6b21a8;
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 0.75rem;
            font-weight: 600;
            margin-left: 4px;
        }
        .remove-btn {
            background: #fee2e2;
            color: #991b1b;
            border: none;
            border-radius: 8px;
            padding: 6px;
            cursor: pointer;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .remove-btn:hover {
            background: #fecaca;
            transform: scale(1.1);
        }
        .item-inputs {
            display: grid;
            grid-template-columns: 80px 1fr 1fr;
            gap: 10px;
        }
        .input-group {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }
        .input-group label {
            font-size: 0.75rem;
            font-weight: 600;
            color: var(--text-secondary);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .qty-input, .price-input {
            padding: 8px 10px;
            border: 1px solid var(--border);
            border-radius: 8px;
            font-size: 0.9rem;
            font-weight: 600;
            outline: none;
            transition: border-color 0.2s;
        }
        .qty-input:focus, .price-input:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
        }
        .total-display {
            justify-content: center;
        }
        .total-value {
            background: white;
            padding: 8px 10px;
            border-radius: 8px;
            font-weight: 700;
            color: var(--primary);
            font-size: 0.95rem;
            text-align: center;
            border: 1px solid #e2e8f0;
        }

        @media (max-width: 768px) {
            .builder-header {
                flex-direction: column;
                text-align: center;
                gap: 20px;
                padding: 35px 20px;
                border-radius: 0 0 35px 35px;
            }
            .header-left h1 {
                font-size: 1.75rem !important;
                letter-spacing: -0.5px;
            }
            .header-right {
                flex-direction: column;
                gap: 18px;
                width: 100%;
            }
            .header-right .company-details {
                text-align: center;
                font-size: 0.85rem;
                opacity: 0.8;
                max-width: 250px;
                margin: 0 auto;
            }
            .view-history-btn {
                width: auto;
                min-width: 200px;
                padding: 14px 35px;
                font-size: 0.95rem;
            }
            
            /* Mobile item cards */
            .item-inputs {
                grid-template-columns: 1fr 1fr;
                gap: 8px;
            }
            .input-group:last-child {
                grid-column: 1 / -1;
            }
            .item-card {
                padding: 12px;
            }
            .editable-field-group {
                flex-wrap: wrap;
            }
            .size-input {
                width: 70px;
            }
            .color-select {
                min-width: 80px;
            }
        }
        
        @media (max-width: 480px) {
            .item-inputs {
                grid-template-columns: 1fr;
            }
            .input-group:last-child {
                grid-column: auto;
            }
        }
    </style>
</div>
