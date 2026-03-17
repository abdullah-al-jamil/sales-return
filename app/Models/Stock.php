<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Stock extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_id',
        'opening_stock',
        'stock_in',
        'stock_out',
        'available_stock',
    ];

    protected $casts = [
        'opening_stock' => 'integer',
        'stock_in' => 'integer',
        'stock_out' => 'integer',
        'available_stock' => 'integer',
    ];

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }
}
