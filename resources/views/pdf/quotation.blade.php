<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Quotation {{ $quotation->quotation_number }}</title>
    <style>
        body { font-family: 'Helvetica', 'Arial', sans-serif; font-size: 11px; color: #333; line-height: 1.4; margin: 0; padding: 20px; border: 1px solid #c9b397; }
        .header-table { width: 100%; border: none; margin-bottom: 20px; border-collapse: collapse; }
        .logo { height: 70px; }
        .quotation-title { font-size: 32px; font-weight: bold; text-align: right; color: #000; letter-spacing: 2px; }
        .company-info { font-weight: bold; font-size: 12px; margin-bottom: 20px; line-height: 1.6; }
        
        .details-header-row { margin-bottom: 10px; border-bottom: 1px solid #000; padding-bottom: 5px; }
        .details-title { font-weight: bold; text-transform: uppercase; display: inline-block; }
        .date-right { float: right; font-weight: bold; }
        
        .items-table { width: 100%; border-collapse: collapse; margin-top: 10px; border: 1px solid #000; }
        .items-table th { background-color: #e6d5c3; color: #000; border: 1px solid #000; padding: 8px; font-weight: bold; text-align: center; }
        .items-table td { border: 1px solid #000; padding: 6px 8px; text-align: left; }
        .center { text-align: center !important; }
        .right { text-align: right !important; }
        
        .total-row { background-color: #f2e8dc; font-weight: 500; }
        .grand-total-row { background-color: #e6d5c3; font-weight: bold; font-size: 12px; }
        
        .customer-section { margin: 15px 0; padding: 10px; border: 1px dashed #c9b397; background-color: #fdfaf7; border-radius: 4px; }
        .customer-title { font-weight: bold; text-transform: uppercase; font-size: 10px; color: #8a735c; margin-bottom: 3px; }

        .terms-section { margin-top: 30px; }
        .terms-title { font-weight: bold; text-transform: uppercase; margin-bottom: 8px; border-bottom: 1px solid #000; width: 200px; }
        .terms-list { margin: 0; padding-left: 18px; list-style-type: disc; }
        .terms-list li { margin-bottom: 5px; }
        
        .footer { position: fixed; bottom: 10px; left: 20px; right: 20px; text-align: center; font-size: 9px; color: #999; }
    </style>
</head>
<body>
    <table class="header-table">
        <tr>
            <td style="width: 50%; vertical-align: top;">
                <img src="{{ public_path('AKM.png') }}" class="logo" alt="AKM Logo">
            </td>
            <td style="width: 50%; vertical-align: middle;">
                <div class="quotation-title">QUOTATION</div>
            </td>
        </tr>
    </table>

    <div class="company-info">
        A.K.M.Aluminium Fabrication<br>
        No.551/6 Kandy road, Malwatta, Nittambuwa.<br>
        0750944571 / 0702098959
    </div>

    @if($quotation->customer_name)
    <div class="customer-section">
        <div class="customer-title">Bill To:</div>
        <div style="font-size: 13px; font-weight: bold;">{{ $quotation->customer_name }}</div>
        @if($quotation->customer_phone)
        <div>Phone: {{ $quotation->customer_phone }}</div>
        @endif
    </div>
    @endif

    <div class="details-header-row">
        <span class="details-title">ITEMIZED QUOTATION DETAILS:</span>
        <span class="date-right">Date: {{ $quotation->date->format('F d, Y') }}</span>
        <div style="font-size: 10px; margin-top: 2px;">Ref: #{{ $quotation->quotation_number }}</div>
    </div>

    <table class="items-table">
        <thead>
            <tr>
                <th style="width: 40%;">Item Description</th>
                <th style="width: 15%;">Size</th>
                <th style="width: 10%;">Quantity</th>
                <th style="width: 17%;">Unit Price (LKR)</th>
                <th style="width: 18%;">Total Price (LKR)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($quotation->items as $item)
            <tr>
                <td>
                    <div style="font-weight: bold;">{{ $item->product_name }}</div>
                    <div style="font-size: 9px; color: #666;">{{ $item->variant }} @if($item->has_louver) (With Louver) @endif</div>
                </td>
                <td class="center">{{ $item->size }}</td>
                <td class="center">{{ $item->quantity }}</td>
                <td class="right">{{ number_format($item->unit_price, 0) }}</td>
                <td class="right">{{ number_format($item->total, 0) }}</td>
            </tr>
            @endforeach
            
            <tr class="total-row">
                <td colspan="4" class="right">Subtotal</td>
                <td class="right">{{ number_format($quotation->subtotal, 0) }}</td>
            </tr>

            @if($quotation->fixed_charge > 0)
            <tr class="total-row">
                <td colspan="4" class="right">Fixed Charge</td>
                <td class="right">{{ number_format($quotation->fixed_charge, 0) }}</td>
            </tr>
            @endif

            @if($quotation->transport_charge > 0)
            <tr class="total-row">
                <td colspan="4" class="right">Transport Charge</td>
                <td class="right">{{ number_format($quotation->transport_charge, 0) }}</td>
            </tr>
            @endif

            @if($quotation->additional_amount > 0)
            <tr class="total-row">
                <td colspan="4" class="right">Additional Charge</td>
                <td class="right">{{ number_format($quotation->additional_amount, 0) }}</td>
            </tr>
            @endif

            <tr class="grand-total-row">
                <td colspan="4" class="right">Total Amount</td>
                <td class="right">{{ number_format($quotation->grand_total, 0) }}</td>
            </tr>
        </tbody>
    </table>

    <div class="terms-section">
        <div class="terms-title">TERMS AND CONDITIONS:</div>
        <ul class="terms-list">
            <li>Validity: This quotation is valid for 1 Week</li>
            <li>50% of the Total Amount must be paid in Advance</li>
            <li>Shipping: Shipping cost is calculated based on the delivery location.</li>
            @if($quotation->additional_notes)
            <li style="font-weight: bold; color: #d32f2f;">Note: {{ $quotation->additional_notes }}</li>
            @endif
        </ul>
    </div>

    <div class="footer">
        Generated by AKM Aluminium Fabrication System
    </div>
</body>
</html>
