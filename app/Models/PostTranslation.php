<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PostTranslation extends Model
{
    use HasFactory;

    protected $fillable = [
        'post_id',
        'lang',
        'title',
        'slug',
        'short_desc',
        'body',
        'seo_title',
        'seo_desc',
    ];

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }
}
