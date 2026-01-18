<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Quotation {{ $quotation->quotation_number }}</title>

    @php
        $logoPath = public_path('AKM.png');
        $logoBase64 = '';
        if (file_exists($logoPath)) {
            $logoBase64 = 'data:image/png;base64,' . base64_encode(file_get_contents($logoPath));
        }
    @endphp

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
            color: #333;
            line-height: 1.4;
            margin: 0;
            padding: 20px;
            border: 1px solid #c9b397;
        }

        .header-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .logo {
            height: 70px;
        }

        .quotation-title {
            font-size: 32px;
            font-weight: bold;
            text-align: right;
            color: #000;
            letter-spacing: 2px;
        }

        .company-info {
            font-weight: bold;
            font-size: 12px;
            margin-bottom: 20px;
            line-height: 1.6;
        }

        .details-header-row {
            margin-bottom: 10px;
            border-bottom: 1px solid #000;
            padding-bottom: 5px;
        }

        .details-title {
            font-weight: bold;
            text-transform: uppercase;
            display: inline-block;
        }

        .date-right {
            float: right;
            font-weight: bold;
        }

        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            border: 1px solid #000;
        }

        .items-table th {
            background-color: #e6d5c3;
            border: 1px solid #000;
            padding: 8px;
            text-align: center;
        }

        .items-table td {
            border: 1px solid #000;
            padding: 6px 8px;
        }

        .center { text-align: center; }
        .right { text-align: right; }

        .total-row {
            background-color: #f2e8dc;
        }

        .grand-total-row {
            background-color: #e6d5c3;
            font-weight: bold;
        }

        .terms-section {
            margin-top: 30px;
        }

        .terms-title {
            font-weight: bold;
            border-bottom: 1px solid #000;
            margin-bottom: 8px;
            width: 220px;
        }

        .terms-list {
            padding-left: 18px;
        }

        .footer {
            position: fixed;
            bottom: 10px;
            left: 20px;
            right: 20px;
            text-align: center;
            font-size: 9px;
            color: #999;
        }
    </style>
</head>

<body>

<table class="header-table">
    <tr>
        <td style="width:50%; vertical-align:top;">
            @if($logoBase64)
                <img src="{{ $logoBase64 }}" class="logo" alt="AKM Logo">
            @endif
        </td>
        <td style="width:50%; vertical-align:middle;">
            <div class="quotation-title">QUOTATION</div>
        </td>
    </tr>
</table>

<div class="company-info">
    A.K.M. Aluminium Fabrication<br>
    No.551/6 Kandy Road, Malwatta, Nittambuwa<br>
    0750944571 / 0702098959
</div>

<div class="details-header-row">
    <span class="details-title">Itemized Quotation Details</span>
    <span class="date-right">Date: {{ $quotation->date->format('F d, Y') }}</span>
    <div style="font-size:10px;">Ref: {{ $quotation->quotation_number }}</div>
</div>

<table class="items-table">
    <thead>
        <tr>
            <th>Item Description</th>
            <th>Size</th>
            <th>Qty</th>
            <th>Unit Price (LKR)</th>
            <th>Total (LKR)</th>
        </tr>
    </thead>
    <tbody>
        @foreach($quotation->items as $item)
        <tr>
            <td>
                <strong>{{ $item->product_name }}</strong><br>
                <small>{{ $item->variant }}</small>
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

        <tr class="grand-total-row">
            <td colspan="4" class="right">Total Amount</td>
            <td class="right">{{ number_format($quotation->grand_total, 0) }}</td>
        </tr>
    </tbody>
</table>

<div class="terms-section">
    <div class="terms-title">Terms & Conditions</div>
    <ul class="terms-list">
        <li>Quotation valid for 1 week</li>
        <li>50% advance payment required</li>
    </ul>
</div>

<div class="footer">
    Generated by AKM Aluminium Fabrication System
</div>

</body>
</html>
