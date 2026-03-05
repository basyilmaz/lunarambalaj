<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CaseStudy extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_name',
        'client_logo',
        'industry',
        'cover_image',
        'is_featured',
        'is_active',
        'order',
    ];

    protected function casts(): array
    {
        return [
            'is_featured' => 'bool',
            'is_active' => 'bool',
            'order' => 'integer',
        ];
    }

    public function translations(): HasMany
    {
        return $this->hasMany(CaseStudyTranslation::class);
    }

    public function translation(string $lang): ?CaseStudyTranslation
    {
        return $this->translations->firstWhere('lang', $lang);
    }
}
