<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductTranslation extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'lang',
        'name',
        'slug',
        'short_desc',
        'description',
        'seo_title',
        'seo_desc',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
