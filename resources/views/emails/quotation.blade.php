<x-mail::message>
# New Quotation Generated

**AKM ALUMINIUM FABRICATION**

Dear Team,

A new quotation has been generated with the following details:

**Quotation Number:** {{ $quotation->quotation_number }}  
**Date:** {{ $quotation->date->format('F d, Y') }}

---

## Customer Information

@if($quotation->customer_name)
**Customer Name:** {{ $quotation->customer_name }}  
@endif

@if($quotation->customer_phone)
**Phone:** {{ $quotation->customer_phone }}  
@endif

---

## Items

@foreach($quotation->items as $index => $item)
**{{ $index + 1 }}. {{ $item->product_name }}**
- Size: {{ $item->size }}
- Color: {{ $item->variant }}
@if($item->has_louver)
- With Louver
@endif
- Quantity: {{ $item->quantity }} × Rs. {{ number_format($item->unit_price, 2) }}
- Total: **Rs. {{ number_format($item->total, 2) }}**

@endforeach

---

## Summary

| Description | Amount (LKR) |
|------------|--------------|
| Subtotal | {{ number_format($quotation->subtotal, 2) }} |
@if($quotation->fixed_charge > 0)
| Fixed Charge | {{ number_format($quotation->fixed_charge, 2) }} |
@endif
@if($quotation->transport_charge > 0)
| Transport Charge | {{ number_format($quotation->transport_charge, 2) }} |
@endif
@if($quotation->additional_amount > 0)
| Additional Charge | {{ number_format($quotation->additional_amount, 2) }} |
@endif
| **Grand Total** | **{{ number_format($quotation->grand_total, 2) }}** |

@if($quotation->additional_notes)
---

## Additional Notes

{{ $quotation->additional_notes }}
@endif

---

**Terms & Conditions:**
- Valid for 1 week
- 50% advance payment required
- Transport calculated by location

**Contact:** 0750944571 / 0702098959  
**Address:** No.551/6 Kandy Rd, Malwatta, Nittambuwa

The detailed PDF quotation is attached to this email.

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
