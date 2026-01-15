<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Quotation Generated</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            background-color: #f8fafc;
            margin: 0;
            padding: 0;
            line-height: 1.6;
        }
        .email-container {
            max-width: 600px;
            margin: 20px auto;
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .header {
            background: #0f172a;
            padding: 30px 20px;
            text-align: center;
        }
        .logo {
            max-width: 120px;
            height: auto;
            margin-bottom: 15px;
        }
        .header-title {
            color: white;
            font-size: 24px;
            font-weight: 700;
            margin: 0;
        }
        .content {
            padding: 30px;
            color: #334155;
        }
        .company-name {
            color: #0f172a;
            font-size: 18px;
            font-weight: 700;
            margin-bottom: 15px;
        }
        .section {
            margin-bottom: 25px;
        }
        .section-title {
            color: #0f172a;
            font-size: 16px;
            font-weight: 700;
            margin-bottom: 10px;
            padding-bottom: 8px;
            border-bottom: 2px solid #e2e8f0;
        }
        .info-row {
            margin: 8px 0;
        }
        .label {
            font-weight: 600;
            color: #64748b;
        }
        .item {
            background: #f8fafc;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 12px;
            border-left: 4px solid #10b981;
        }
        .item-title {
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 8px;
        }
        .item-detail {
            font-size: 14px;
            color: #64748b;
            margin: 4px 0;
        }
        .summary-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        .summary-table td {
            padding: 10px;
            border-bottom: 1px solid #e2e8f0;
        }
        .summary-table .label-col {
            font-weight: 600;
            color: #64748b;
        }
        .summary-table .amount-col {
            text-align: right;
            font-weight: 600;
            color: #0f172a;
        }
        .summary-table .total-row td {
            background: #f1f5f9;
            font-weight: 700;
            font-size: 16px;
            color: #0f172a;
            border-bottom: none;
        }
        .terms {
            background: #fef3c7;
            padding: 15px;
            border-radius: 8px;
            border-left: 4px solid #f59e0b;
        }
        .terms ul {
            margin: 10px 0;
            padding-left: 20px;
        }
        .terms li {
            margin: 5px 0;
        }
        .footer {
            background: #f8fafc;
            padding: 20px;
            text-align: center;
            color: #64748b;
            font-size: 14px;
        }
        .attachment-note {
            background: #dbeafe;
            padding: 15px;
            border-radius: 8px;
            text-align: center;
            color: #1e40af;
            font-weight: 600;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <img src="{{ $message->embed(public_path('AKM.png')) }}" alt="AKM Logo" class="logo">
            <h1 class="header-title">New Quotation Generated</h1>
        </div>

        <div class="content">
            <div class="company-name">AKM ALUMINIUM FABRICATION</div>
            
            <p>Dear Team,</p>
            <p>A new quotation has been generated with the following details:</p>

            <div class="section">
                <div class="info-row">
                    <span class="label">Quotation Number:</span> <strong>{{ $quotation->quotation_number }}</strong>
                </div>
                <div class="info-row">
                    <span class="label">Date:</span> {{ $quotation->date->format('F d, Y') }}
                </div>
            </div>

            @if($quotation->customer_name || $quotation->customer_phone)
            <div class="section">
                <div class="section-title">Customer Information</div>
                @if($quotation->customer_name)
                <div class="info-row">
                    <span class="label">Customer Name:</span> {{ $quotation->customer_name }}
                </div>
                @endif
                @if($quotation->customer_phone)
                <div class="info-row">
                    <span class="label">Phone:</span> {{ $quotation->customer_phone }}
                </div>
                @endif
            </div>
            @endif

            <div class="section">
                <div class="section-title">Items</div>
                @foreach($quotation->items as $index => $item)
                <div class="item">
                    <div class="item-title">{{ $index + 1 }}. {{ $item->product_name }}</div>
                    <div class="item-detail">Size: {{ $item->size }}</div>
                    <div class="item-detail">Color: {{ $item->variant }}</div>
                    @if($item->has_louver)
                    <div class="item-detail">✓ With Louver</div>
                    @endif
                    @if($item->has_fix_glass)
                    <div class="item-detail">✓ With Fix Glass</div>
                    @endif
                    <div class="item-detail">Quantity: {{ $item->quantity }} × Rs. {{ number_format($item->unit_price, 2) }}</div>
                    <div class="item-detail"><strong>Total: Rs. {{ number_format($item->total, 2) }}</strong></div>
                </div>
                @endforeach
            </div>

            <div class="section">
                <div class="section-title">Summary</div>
                <table class="summary-table">
                    <tr>
                        <td class="label-col">Subtotal</td>
                        <td class="amount-col">Rs. {{ number_format($quotation->subtotal, 2) }}</td>
                    </tr>
                    @if($quotation->fixed_charge > 0)
                    <tr>
                        <td class="label-col">Fixed Charge</td>
                        <td class="amount-col">Rs. {{ number_format($quotation->fixed_charge, 2) }}</td>
                    </tr>
                    @endif
                    @if($quotation->transport_charge > 0)
                    <tr>
                        <td class="label-col">Transport Charge</td>
                        <td class="amount-col">Rs. {{ number_format($quotation->transport_charge, 2) }}</td>
                    </tr>
                    @endif
                    @if($quotation->additional_amount > 0)
                    <tr>
                        <td class="label-col">Additional Charge</td>
                        <td class="amount-col">Rs. {{ number_format($quotation->additional_amount, 2) }}</td>
                    </tr>
                    @endif
                    <tr class="total-row">
                        <td class="label-col">GRAND TOTAL</td>
                        <td class="amount-col">Rs. {{ number_format($quotation->grand_total, 2) }}</td>
                    </tr>
                </table>
            </div>

            @if($quotation->additional_notes)
            <div class="section">
                <div class="section-title">Additional Notes</div>
                <p>{{ $quotation->additional_notes }}</p>
            </div>
            @endif

            <div class="section">
                <div class="terms">
                    <strong>Terms & Conditions:</strong>
                    <ul>
                        <li>Valid for 1 week</li>
                        <li>50% advance payment required</li>
                        <li>Transport calculated by location</li>
                    </ul>
                </div>
            </div>

            <div class="attachment-note">
                📎 The detailed PDF quotation is attached to this email
            </div>
        </div>

        <div class="footer">
            <strong>Contact:</strong> 0750944571 / 0702098959<br>
            <strong>Address:</strong> No.551/6 Kandy Rd, Malwatta, Nittambuwa<br><br>
            <p style="margin: 10px 0 0 0; color: #94a3b8;">© {{ date('Y') }} AKM Aluminium Fabrication. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
