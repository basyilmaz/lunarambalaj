<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProductCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'order',
        'is_active',
    ];

    public function translations(): HasMany
    {
        return $this->hasMany(ProductCategoryTranslation::class);
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class, 'category_id');
    }

    public function translation(string $lang): ?ProductCategoryTranslation
    {
        return $this->translations->firstWhere('lang', $lang)
            ?? $this->translations->firstWhere('lang', (string) config('app.fallback_locale'))
            ?? $this->translations->firstWhere('lang', 'tr')
            ?? $this->translations->firstWhere('lang', 'en')
            ?? $this->translations->first();
    }
}
