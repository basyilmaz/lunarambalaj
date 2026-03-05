<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'category_id',
        'min_order',
        'has_print',
        'has_wrapping',
        'is_active',
        'image',
        'images',
        'specs',
    ];

    protected function casts(): array
    {
        return [
            'has_print' => 'bool',
            'has_wrapping' => 'bool',
            'is_active' => 'bool',
            'images' => 'array',
            'specs' => 'array',
            'deleted_at' => 'datetime',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(ProductCategory::class, 'category_id');
    }

    public function translations(): HasMany
    {
        return $this->hasMany(ProductTranslation::class);
    }

    public function translation(string $lang): ?ProductTranslation
    {
        return $this->translations->firstWhere('lang', $lang)
            ?? $this->translations->firstWhere('lang', (string) config('app.fallback_locale'))
            ?? $this->translations->firstWhere('lang', 'tr')
            ?? $this->translations->firstWhere('lang', 'en')
            ?? $this->translations->first();
    }
}
