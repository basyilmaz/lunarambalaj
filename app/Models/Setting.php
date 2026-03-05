<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'phone',
        'email',
        'email_secondary',
        'address',
        'working_hours',
        'whatsapp',
        'facebook',
        'instagram',
        'linkedin',
        'gtm_id',
        'meta_pixel_id',
        'min_order_default',
        'company_name_tr',
        'company_name_en',
        'hero_h1_tr',
        'hero_h1_en',
        'hero_subtitle_tr',
        'hero_subtitle_en',
        'footer_short_tr',
        'footer_short_en',
        'latitude',
        'longitude',
    ];
}
