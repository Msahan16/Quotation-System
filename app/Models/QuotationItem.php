<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QuotationItem extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'unit_price' => 'decimal:2',
        'total' => 'decimal:2',
        'has_louver' => 'boolean',
        'has_fix_glass' => 'boolean',
        'has_key_lock' => 'boolean',
        'has_fiber_board' => 'boolean',
    ];

    public function quotation(): BelongsTo
    {
        return $this->belongsTo(Quotation::class);
    }
}
