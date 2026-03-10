<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WebhookDelivery extends Model
{
    use HasFactory;

    protected $fillable = [
        'webhook_endpoint_id',
        'event',
        'status_code',
        'success',
        'response_time_ms',
        'attempt',
        'payload',
        'signature',
    ];

    protected function casts(): array
    {
        return [
            'status_code' => 'integer',
            'success' => 'boolean',
            'response_time_ms' => 'integer',
            'attempt' => 'integer',
            'payload' => 'array',
        ];
    }

    public function endpoint(): BelongsTo
    {
        return $this->belongsTo(WebhookEndpoint::class, 'webhook_endpoint_id');
    }
}
