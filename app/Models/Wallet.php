<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Wallet extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'type',
        'provider',
        'chain_id',
        'label',
        'status',
        'address',
        'xpub_key',
        'derivation_path',
        'api_key_encrypted',
        'api_secret_encrypted',
        'api_passphrase_encrypted',
        'addresses_generated',
        'total_received',
        'last_synced_at',
    ];

    protected function casts(): array
    {
        return [
            'addresses_generated' => 'integer',
            'total_received' => 'decimal:8',
            'last_synced_at' => 'datetime',
            'api_key_encrypted' => 'encrypted',
            'api_secret_encrypted' => 'encrypted',
            'api_passphrase_encrypted' => 'encrypted',
        ];
    }

    // ─── Relations ───

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    // ─── Scopes ───

    public function scopeOfType($query, string $type)
    {
        return $query->where('type', $type);
    }

    public function scopeActive($query)
    {
        return $query->whereNotIn('status', ['paused', 'error']);
    }

    public function scopeForChain($query, string $chainId)
    {
        return $query->where('chain_id', $chainId);
    }

    // ─── Accessors ───

    public function getDisplayIdentifierAttribute(): string
    {
        return match ($this->type) {
            'exchange' => strtoupper($this->provider) . ' API',
            'xpub' => substr($this->xpub_key, 0, 12) . '...' . substr($this->xpub_key, -8),
            'software', 'manual' => substr($this->address, 0, 10) . '...' . substr($this->address, -6),
            default => $this->label,
        };
    }
}
