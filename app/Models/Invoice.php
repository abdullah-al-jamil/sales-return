<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_name',
        'invoice_date',
        'taxable_amount',
        'discount_amount',
        'vat_amount',
        'total_amount',
        'status',
    ];

    protected $casts = [
        'invoice_date' => 'datetime',
        'taxable_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'vat_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
    ];

    public function items(): BelongsToMany
    {
        return $this->belongsToMany(Item::class, 'invoice_items')
            ->withPivot('id', 'quantity', 'unit_price', 'taxable_price', 'discount', 'tax_rate', 'total')
            ->withTimestamps();
    }

    public function invoiceItems(): HasMany
    {
        return $this->hasMany(InvoiceItem::class);
    }

    public function salesReturns(): HasMany
    {
        return $this->hasMany(SalesReturn::class);
    }
}
