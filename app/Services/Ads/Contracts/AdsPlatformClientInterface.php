<?php

namespace App\Services\Ads\Contracts;

use App\Models\AdIntegration;
use Carbon\CarbonInterface;

interface AdsPlatformClientInterface
{
    /**
     * @return array<int, array<string, mixed>>
     */
    public function fetchCampaignSnapshots(AdIntegration $integration, CarbonInterface $from, CarbonInterface $to): array;
}

