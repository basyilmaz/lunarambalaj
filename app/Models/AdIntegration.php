<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Crypt;
use Throwable;

class AdIntegration extends Model
{
    use HasFactory;

    protected $fillable = [
        'platform',
        'name',
        'credentials',
        'credentials_encrypted',
        'is_active',
        'last_sync_at',
        'last_sync_status',
        'last_sync_error',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'bool',
            'last_sync_at' => 'datetime',
            'last_sync_status' => 'string',
        ];
    }

    public function syncLogs(): HasMany
    {
        return $this->hasMany(AdsSyncLog::class);
    }

    /**
     * @return array<string, mixed>
     */
    public function getCredentialsAttribute($value): array
    {
        $encrypted = $this->attributes['credentials_encrypted'] ?? null;

        if (is_string($encrypted) && $encrypted !== '') {
            try {
                $decrypted = Crypt::decryptString($encrypted);
                $decoded = json_decode($decrypted, true);

                return is_array($decoded) ? $decoded : [];
            } catch (Throwable) {
                // Fallback to legacy plaintext field below.
            }
        }

        if (is_array($value)) {
            return $value;
        }

        if (is_string($value) && $value !== '') {
            $decoded = json_decode($value, true);

            return is_array($decoded) ? $decoded : [];
        }

        return [];
    }

    /**
     * @param  array<string, mixed>|string|null  $value
     */
    public function setCredentialsAttribute($value): void
    {
        if (is_string($value) && $value !== '') {
            $decoded = json_decode($value, true);
            $value = is_array($decoded) ? $decoded : [];
        }

        $credentials = is_array($value) ? $value : [];

        if ($credentials === []) {
            $this->attributes['credentials'] = null;
            $this->attributes['credentials_encrypted'] = null;

            return;
        }

        $json = json_encode($credentials, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        $this->attributes['credentials'] = $json;
        $this->attributes['credentials_encrypted'] = Crypt::encryptString($json);
    }
}
