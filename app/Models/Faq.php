<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Faq extends Model
{
    use HasFactory;

    protected $fillable = [
        'category',
        'order',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'bool',
        ];
    }

    public function translations(): HasMany
    {
        return $this->hasMany(FaqTranslation::class);
    }

    public function translation(string $lang): ?FaqTranslation
    {
        return $this->translations->firstWhere('lang', $lang)
            ?? $this->translations->firstWhere('lang', (string) config('app.fallback_locale'))
            ?? $this->translations->firstWhere('lang', 'tr')
            ?? $this->translations->firstWhere('lang', 'en')
            ?? $this->translations->first();
    }
}
