<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'cover',
        'published_at',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'published_at' => 'datetime',
            'is_active' => 'bool',
            'deleted_at' => 'datetime',
        ];
    }

    public function translations(): HasMany
    {
        return $this->hasMany(PostTranslation::class);
    }

    public function translation(string $lang): ?PostTranslation
    {
        return $this->translations->firstWhere('lang', $lang)
            ?? $this->translations->firstWhere('lang', (string) config('app.fallback_locale'))
            ?? $this->translations->firstWhere('lang', 'tr')
            ?? $this->translations->firstWhere('lang', 'en')
            ?? $this->translations->first();
    }
}
