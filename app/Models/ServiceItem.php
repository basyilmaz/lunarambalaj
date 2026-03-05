<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ServiceItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'icon',
        'order',
        'is_active',
    ];

    public function translations(): HasMany
    {
        return $this->hasMany(ServiceItemTranslation::class);
    }

    public function translation(string $lang): ?ServiceItemTranslation
    {
        return $this->translations->firstWhere('lang', $lang);
    }
}
