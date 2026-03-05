<?php

namespace App\Services\Ads;

use App\Services\Ads\Clients\GoogleAdsClient;
use App\Services\Ads\Clients\MetaAdsClient;
use App\Services\Ads\Contracts\AdsPlatformClientInterface;
use InvalidArgumentException;

class AdsClientFactory
{
    public function make(string $platform): AdsPlatformClientInterface
    {
        return match ($platform) {
            'google_ads' => app(GoogleAdsClient::class),
            'meta_ads' => app(MetaAdsClient::class),
            default => throw new InvalidArgumentException("Unsupported ads platform: {$platform}"),
        };
    }
}

