<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AdsSyncLog extends Model
{
    use HasFactory;

    public $timestamps = false;

    const UPDATED_AT = null;

    protected $fillable = [
        'ad_integration_id',
        'platform',
        'status',
        'from_date',
        'to_date',
        'fetched',
        'upserted',
        'error_message',
        'context',
        'created_at',
    ];

    protected function casts(): array
    {
        return [
            'from_date' => 'date',
            'to_date' => 'date',
            'fetched' => 'integer',
            'upserted' => 'integer',
            'context' => 'array',
            'created_at' => 'datetime',
        ];
    }

    public function integration(): BelongsTo
    {
        return $this->belongsTo(AdIntegration::class, 'ad_integration_id');
    }
}

