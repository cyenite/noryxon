<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ApiKey extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'key_hash',
        'key_prefix',
        'is_test',
        'last_used_at',
        'ip_whitelist',
        'requests_today',
    ];

    protected $hidden = [
        'key_hash',
    ];

    protected function casts(): array
    {
        return [
            'is_test' => 'boolean',
            'last_used_at' => 'datetime',
            'ip_whitelist' => 'array',
            'requests_today' => 'integer',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Generate a new API key and return the plain text version.
     * The hashed version is stored in key_hash.
     */
    public static function generateKey(bool $isTest = false): string
    {
        $prefix = $isTest ? 'sk_test_' : 'sk_live_';
        $chars = 'abcdef0123456789';
        $random = '';
        for ($i = 0; $i < 32; $i++) {
            $random .= $chars[random_int(0, strlen($chars) - 1)];
        }
        return $prefix . $random;
    }
}
