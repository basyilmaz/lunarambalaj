<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CaseStudyTranslation extends Model
{
    use HasFactory;

    protected $fillable = [
        'case_study_id',
        'lang',
        'title',
        'slug',
        'challenge',
        'solution',
        'results',
        'seo_title',
        'seo_desc',
    ];

    public function caseStudy(): BelongsTo
    {
        return $this->belongsTo(CaseStudy::class);
    }
}
