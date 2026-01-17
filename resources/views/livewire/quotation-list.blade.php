<div class="quotation-list-container" style="background: #f8fafc; min-height: 100vh; font-family: 'Outfit', sans-serif; color: #1e293b;">
    <!-- Modern Header -->
    <div style="background: #0f172a; padding: 40px 20px 40px; border-radius: 0 0 30px 30px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); margin-bottom: 25px;">
        <div style="max-width: 800px; margin: 0 auto; text-align: center;">
            <h1 style="color: #fff; font-size: 2rem; font-weight: 700; margin: 0; letter-spacing: -0.5px;">Quotation History</h1>
            <p style="color: #94a3b8; margin: 8px 0 25px; font-size: 1rem;">Track and manage all your documents</p>
            <a href="{{ route('quotation.builder') }}" wire:navigate style="display: inline-flex; align-items: center; gap: 8px; background: #10b981; color: white; padding: 12px 30px; border-radius: 99px; font-weight: 600; text-decoration: none; box-shadow: 0 4px 15px rgba(16, 185, 129, 0.3); transition: transform 0.2s;" onmousedown="this.style.transform='scale(0.95)'" onmouseup="this.style.transform='scale(1)'">
                <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M12 4v16m8-8H4"/></svg>
                Create New Quotation
            </a>
        </div>
    </div>

    <div style="max-width: 850px; margin: 0 auto; padding: 0 15px 40px;">
        <!-- Refined Search Bar -->
        <div style="margin-bottom: 30px;">
            <div style="position: relative; background: white; border-radius: 16px; padding: 2px; box-shadow: 0 10px 25px -5px rgba(0,0,0,0.05); border: 1px solid #e2e8f0; display: flex; align-items: center;">
                <span style="position: absolute; left: 18px; color: #94a3b8;">
                    <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </span>
                <input wire:model.live="search" type="text" placeholder="Search quotation # or customer name..." style="width: 100%; border: none; background: transparent; padding: 18px 18px 18px 52px; font-size: 1rem; outline: none; font-family: inherit; color: #1e293b;">
            </div>
        </div>

        @if (session()->has('message'))
            <div style="background: #f0fdf4; border-left: 4px solid #10b981; padding: 15px 20px; border-radius: 12px; margin-bottom: 25px; color: #166534; font-weight: 500; font-size: 0.95rem; box-shadow: 0 2px 5px rgba(0,0,0,0.02);">
                {{ session('message') }}
            </div>
        @endif

        <!-- Quotation Cards -->
        <div style="display: grid; gap: 18px;">
            @forelse($quotations as $quotation)
                <div class="quotation-card" style="background: white; border-radius: 24px; padding: 20px; border: 1px solid #eef2f6; box-shadow: 0 4px 10px rgba(0,0,0,0.03); transition: all 0.2s;">
                    <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 18px;">
                        <div>
                            <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 4px;">
                                <span style="background: #f1f5f9; color: #0f172a; padding: 4px 10px; border-radius: 8px; font-size: 0.85rem; font-weight: 700;">#{{ $quotation->quotation_number }}</span>
                                <span style="font-size: 0.8rem; color: #94a3b8;">{{ \Carbon\Carbon::parse($quotation->date)->format('M d, Y') }}</span>
                            </div>
                            <h3 style="font-size: 1.15rem; font-weight: 700; color: #1e293b; margin: 0;">{{ $quotation->customer_name ?: 'Guest Customer' }}</h3>
                            @if($quotation->customer_phone)
                                <div style="font-size: 0.85rem; color: #64748b; margin-top: 2px;">{{ $quotation->customer_phone }}</div>
                            @endif
                        </div>
                        <div style="text-align: right;">
                            <div style="font-size: 0.75rem; color: #94a3b8; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">Grand Total</div>
                            <div style="font-size: 1.25rem; font-weight: 800; color: #0f172a; margin-top: 2px;">Rs. {{ number_format($quotation->grand_total, 0) }}</div>
                        </div>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr auto; gap: 10px; padding-top: 15px; border-top: 1px solid #f1f5f9;">
                        <button wire:click="shareToWhatsApp({{ $quotation->id }})" style="background: #22c55e; color: white; border: none; padding: 12px 15px; border-radius: 12px; font-weight: 600; font-size: 0.9rem; display: flex; align-items: center; justify-content: center; gap: 8px; cursor: pointer; transition: background 0.2s;">
                            <svg width="20" height="20" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>
                            Share on WhatsApp
                        </button>
                        <div style="display: flex; gap: 8px;">
                            <a href="{{ route('quotation.view', $quotation->id) }}" wire:navigate style="background: #dbeafe; color: #1e40af; padding: 12px; border-radius: 12px; display: flex; align-items: center; justify-content: center; transition: background 0.2s;" title="View Quotation">
                                <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            </a>
                            <a href="{{ route('quotation.download', $quotation) }}" style="background: #e0e7ff; color: #3730a3; padding: 12px; border-radius: 12px; display: flex; align-items: center; justify-content: center; transition: background 0.2s;" title="Download PDF">
                                <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path d="M4 16v1a2 2 0 002 2h12a2 2 0 002-2v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                            </a>
                            <a href="{{ route('quotation.edit', $quotation->id) }}" wire:navigate style="background: #fef3c7; color: #92400e; padding: 12px; border-radius: 12px; display: flex; align-items: center; justify-content: center; transition: background 0.2s;" title="Edit Quotation">
                                <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            </a>
                            <button wire:click="deleteQuotation({{ $quotation->id }})" wire:confirm="Remove this quotation?" style="background: #fee2e2; color: #991b1b; padding: 12px; border-radius: 12px; border: none; cursor: pointer; display: flex; align-items: center; justify-content: center; transition: background 0.2s;" title="Delete">
                                <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </button>
                        </div>
                    </div>
                </div>
            @empty
                <div style="text-align: center; padding: 60px 20px; background: white; border-radius: 24px; border: 2px dashed #e2e8f0;">
                    <svg width="48" height="48" fill="none" viewBox="0 0 24 24" stroke="#94a3b8" stroke-width="1.5" style="margin-bottom: 15px;"><path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    <p style="color: #64748b; font-size: 1.1rem; font-weight: 500; margin: 0;">No quotations found yet.</p>
                </div>
            @endforelse
        </div>

        @if($quotations->hasPages())
            <div style="margin-top: 30px;">
                {{ $quotations->links() }}
            </div>
        @endif
    </div>

    <style>
        .quotation-card:hover { transform: translateY(-3px); box-shadow: 0 12px 30px rgba(0,0,0,0.06); border-color: #cbd5e1; }
        @media (max-width: 600px) {
            .quotation-list-container { padding-top: 5px !important; }
            .quotation-card { padding: 16px !important; }
            h1 { font-size: 1.6rem !important; }
            .quotation-card > div:last-child { grid-template-columns: 1fr !important; }
            .quotation-card > div:last-child > div { justify-content: space-between !important; }
            .quotation-card > div:last-child > div > * { flex: 1 !important; }
        }
    </style>

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
            // Handle WhatsApp sharing
            Livewire.on('open-whatsapp', (event) => {
                try {
                    const data = Array.isArray(event) ? event[0] : event;
                    console.log('Opening WhatsApp with URL:', data.url);
                    
                    if (data.url) {
                        window.open(data.url, '_blank');
                    } else {
                        console.error('No WhatsApp URL provided');
                    }
                } catch (error) {
                    console.error('Error opening WhatsApp:', error);
                }
            });
        }
    </script>
</div>
