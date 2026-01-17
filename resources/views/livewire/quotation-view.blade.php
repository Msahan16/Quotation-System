<div style="background: #f8fafc; min-height: 100vh; font-family: 'Outfit', sans-serif; color: #1e293b; padding-bottom: 40px;">
    <!-- Header -->
    <div style="background: #0f172a; padding: 30px 20px; border-radius: 0 0 30px 30px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); margin-bottom: 30px;">
        <div style="max-width: 900px; margin: 0 auto; display: flex; justify-content: space-between; align-items: center;">
            <div>
                <h1 style="color: #fff; font-size: 1.75rem; font-weight: 700; margin: 0;">Quotation Details</h1>
                <p style="color: #94a3b8; margin: 5px 0 0; font-size: 0.95rem;">{{ $quotation->quotation_number }}</p>
            </div>
            <a href="{{ route('quotation.list') }}" wire:navigate style="background: rgba(255, 255, 255, 0.1); color: white; padding: 10px 20px; border-radius: 12px; text-decoration: none; font-weight: 600; font-size: 0.9rem; display: inline-flex; align-items: center; gap: 8px; transition: background 0.2s;">
                <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Back to List
            </a>
        </div>
    </div>

    <div style="max-width: 900px; margin: 0 auto; padding: 0 15px;">
        <!-- Customer Info Card -->
        <div style="background: white; border-radius: 20px; padding: 25px; margin-bottom: 20px; box-shadow: 0 4px 10px rgba(0,0,0,0.03); border: 1px solid #eef2f6;">
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px;">
                <div>
                    <div style="font-size: 0.75rem; color: #94a3b8; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 5px;">Customer Name</div>
                    <div style="font-size: 1.1rem; font-weight: 600; color: #1e293b;">{{ $quotation->customer_name ?: 'Guest Customer' }}</div>
                </div>
                @if($quotation->customer_phone)
                <div>
                    <div style="font-size: 0.75rem; color: #94a3b8; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 5px;">Phone Number</div>
                    <div style="font-size: 1.1rem; font-weight: 600; color: #1e293b;">{{ $quotation->customer_phone }}</div>
                </div>
                @endif
                <div>
                    <div style="font-size: 0.75rem; color: #94a3b8; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 5px;">Date</div>
                    <div style="font-size: 1.1rem; font-weight: 600; color: #1e293b;">{{ \Carbon\Carbon::parse($quotation->date)->format('M d, Y') }}</div>
                </div>
            </div>
        </div>

        <!-- Items List -->
        <div style="background: white; border-radius: 20px; padding: 25px; margin-bottom: 20px; box-shadow: 0 4px 10px rgba(0,0,0,0.03); border: 1px solid #eef2f6;">
            <h2 style="font-size: 1.25rem; font-weight: 700; color: #1e293b; margin: 0 0 20px;">Items</h2>
            
            <div style="display: flex; flex-direction: column; gap: 15px;">
                @foreach($quotation->items as $index => $item)
                    <div style="background: #f8fafc; border-radius: 12px; padding: 15px; border: 1px solid #e2e8f0;">
                        <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 10px;">
                            <div style="flex: 1;">
                                <div style="font-weight: 700; font-size: 1rem; color: #1e293b; margin-bottom: 5px;">{{ $index + 1 }}. {{ $item->product_name }}</div>
                                <div style="font-size: 0.85rem; color: #64748b;">
                                    <span>{{ $item->size }}</span> | <span>{{ $item->variant }}</span>
                                    @if($item->has_louver) <span style="background: #dbeafe; color: #1e40af; padding: 2px 6px; border-radius: 4px; font-size: 0.75rem; font-weight: 600; margin-left: 5px;">+ Louver</span> @endif
                                    @if($item->has_fix_glass) <span style="background: #d1fae5; color: #065f46; padding: 2px 6px; border-radius: 4px; font-size: 0.75rem; font-weight: 600; margin-left: 5px;">+ Fix Glass</span> @endif
                                    @if($item->has_key_lock) <span style="background: #fed7aa; color: #92400e; padding: 2px 6px; border-radius: 4px; font-size: 0.75rem; font-weight: 600; margin-left: 5px;">+ Key Lock</span> @endif
                                    @if($item->has_fiber_board) <span style="background: #e9d5ff; color: #6b21a8; padding: 2px 6px; border-radius: 4px; font-size: 0.75rem; font-weight: 600; margin-left: 5px;">+ Fiber Board</span> @endif
                                </div>
                            </div>
                        </div>
                        <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 15px; padding-top: 10px; border-top: 1px solid #e2e8f0;">
                            <div>
                                <div style="font-size: 0.7rem; color: #94a3b8; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 3px;">Quantity</div>
                                <div style="font-size: 0.95rem; font-weight: 600; color: #1e293b;">{{ $item->quantity }}</div>
                            </div>
                            <div>
                                <div style="font-size: 0.7rem; color: #94a3b8; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 3px;">Unit Price</div>
                                <div style="font-size: 0.95rem; font-weight: 600; color: #1e293b;">Rs. {{ number_format($item->unit_price, 2) }}</div>
                            </div>
                            <div>
                                <div style="font-size: 0.7rem; color: #94a3b8; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 3px;">Total</div>
                                <div style="font-size: 0.95rem; font-weight: 700; color: #10b981;">Rs. {{ number_format($item->total, 2) }}</div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Totals Card -->
        <div style="background: white; border-radius: 20px; padding: 25px; margin-bottom: 20px; box-shadow: 0 4px 10px rgba(0,0,0,0.03); border: 1px solid #eef2f6;">
            <h2 style="font-size: 1.25rem; font-weight: 700; color: #1e293b; margin: 0 0 15px;">Summary</h2>
            
            <div style="display: flex; flex-direction: column; gap: 10px;">
                <div style="display: flex; justify-content: space-between; padding: 8px 0;">
                    <span style="color: #64748b; font-weight: 500;">Subtotal</span>
                    <span style="font-weight: 600; color: #1e293b;">Rs. {{ number_format($quotation->subtotal, 2) }}</span>
                </div>
                
                @if($quotation->fixed_charge > 0)
                <div style="display: flex; justify-content: space-between; padding: 8px 0;">
                    <span style="color: #64748b; font-weight: 500;">Fixed Charge</span>
                    <span style="font-weight: 600; color: #1e293b;">Rs. {{ number_format($quotation->fixed_charge, 2) }}</span>
                </div>
                @endif
                
                @if($quotation->transport_charge > 0)
                <div style="display: flex; justify-content: space-between; padding: 8px 0;">
                    <span style="color: #64748b; font-weight: 500;">Transport Charge</span>
                    <span style="font-weight: 600; color: #1e293b;">Rs. {{ number_format($quotation->transport_charge, 2) }}</span>
                </div>
                @endif
                
                @if($quotation->additional_amount > 0)
                <div style="display: flex; justify-content: space-between; padding: 8px 0;">
                    <span style="color: #64748b; font-weight: 500;">Additional Amount</span>
                    <span style="font-weight: 600; color: #1e293b;">Rs. {{ number_format($quotation->additional_amount, 2) }}</span>
                </div>
                @endif
                
                <div style="display: flex; justify-content: space-between; padding: 15px 0; border-top: 2px solid #e2e8f0; margin-top: 5px;">
                    <span style="font-size: 1.1rem; font-weight: 700; color: #1e293b;">Grand Total</span>
                    <span style="font-size: 1.3rem; font-weight: 800; color: #10b981;">Rs. {{ number_format($quotation->grand_total, 2) }}</span>
                </div>
            </div>
            
            @if($quotation->additional_notes)
            <div style="margin-top: 20px; padding: 15px; background: #f8fafc; border-radius: 10px; border-left: 4px solid #10b981;">
                <div style="font-size: 0.75rem; color: #94a3b8; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 5px;">Additional Notes</div>
                <div style="color: #1e293b; font-size: 0.95rem; line-height: 1.6;">{{ $quotation->additional_notes }}</div>
            </div>
            @endif
        </div>

        <!-- Action Buttons -->
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 12px;">
            <button onclick="safeDownload('{{ route('quotation.download', $quotation) }}', 'Quotation-{{ $quotation->quotation_number }}.pdf')" style="background: #10b981; color: white; padding: 14px 20px; border-radius: 12px; font-weight: 600; text-align: center; display: flex; align-items: center; justify-content: center; gap: 8px; transition: background 0.2s; border: none; cursor: pointer; width: 100%;">
                <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path d="M4 16v1a2 2 0 002 2h12a2 2 0 002-2v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                Download PDF
            </button>
            <a href="{{ route('quotation.edit', $quotation->id) }}" wire:navigate style="background: #f59e0b; color: white; padding: 14px 20px; border-radius: 12px; text-decoration: none; font-weight: 600; text-align: center; display: flex; align-items: center; justify-content: center; gap: 8px; transition: background 0.2s;">
                <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                Edit Quotation
            </a>
        </div>
    </div>

    <style>
        @media (max-width: 768px) {
            h1 { font-size: 1.4rem !important; }
            h2 { font-size: 1.1rem !important; }
        }
    </style>

    <script>
        // Safe download function to prevent browser blocking
        async function safeDownload(url, filename) {
            try {
                // Show loading state (optional - you can add a visual indicator)
                console.log('Starting download:', filename);
                
                // Fetch the file
                const response = await fetch(url, {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/pdf',
                    },
                });

                if (!response.ok) {
                    throw new Error('Download failed');
                }

                // Get the blob
                const blob = await response.blob();
                
                // Create a temporary URL for the blob
                const blobUrl = window.URL.createObjectURL(blob);
                
                // Create a temporary anchor element
                const link = document.createElement('a');
                link.href = blobUrl;
                link.download = filename;
                link.style.display = 'none';
                
                // Append to body, click, and remove
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
                
                // Clean up the blob URL after a short delay
                setTimeout(() => {
                    window.URL.revokeObjectURL(blobUrl);
                }, 100);
                
                console.log('Download completed:', filename);
            } catch (error) {
                console.error('Download error:', error);
                
                // Fallback: Try opening in new window if fetch fails
                try {
                    const fallbackWindow = window.open(url, '_blank');
                    if (!fallbackWindow) {
                        // If popup was blocked, show user message
                        alert('Please allow popups for this site to download the quotation, or try again.');
                    }
                } catch (fallbackError) {
                    alert('Unable to download. Please check your browser settings and try again.');
                }
            }
        }
    </script>
</div>
