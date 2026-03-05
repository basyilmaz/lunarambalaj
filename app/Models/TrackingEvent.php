<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TrackingEvent extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_key',
        'display_name',
        'schema',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'schema' => 'array',
            'is_active' => 'bool',
        ];
    }

    public function conversionMappings(): HasMany
    {
        return $this->hasMany(ConversionMapping::class);
    }
}

