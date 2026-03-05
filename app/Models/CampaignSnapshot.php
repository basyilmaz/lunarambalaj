<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CampaignSnapshot extends Model
{
    use HasFactory;

    protected $fillable = [
        'platform',
        'campaign_id',
        'campaign_name',
        'snapshot_date',
        'spend',
        'clicks',
        'impressions',
        'conversions',
    ];

    protected function casts(): array
    {
        return [
            'snapshot_date' => 'date',
            'spend' => 'decimal:2',
        ];
    }
}

