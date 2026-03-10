<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'xpub_id',
        'tx_hash',
        'amount',
        'token',
        'chain_id',
        'status',
        'confirmations',
        'required_confirmations',
        'customer_wallet',
        'merchant_wallet',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:8',
            'confirmations' => 'integer',
            'required_confirmations' => 'integer',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function xpub(): BelongsTo
    {
        return $this->belongsTo(Xpub::class);
    }
}
