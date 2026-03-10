<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Xpub extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'chain_id',
        'label',
        'xpub_key',
        'derivation_path',
        'status',
        'addresses_generated',
        'total_received',
    ];

    protected function casts(): array
    {
        return [
            'addresses_generated' => 'integer',
            'total_received' => 'decimal:8',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }
}
