<?php

namespace App\Services\Ads\Clients;

use App\Models\AdIntegration;
use App\Services\Ads\Contracts\AdsPlatformClientInterface;
use Carbon\CarbonInterface;
use Illuminate\Support\Facades\Http;
use Throwable;

class GoogleAdsClient implements AdsPlatformClientInterface
{
    public function fetchCampaignSnapshots(AdIntegration $integration, CarbonInterface $from, CarbonInterface $to): array
    {
        $credentials = $integration->credentials ?? [];
        $apiToken = (string) ($credentials['api_token'] ?? config('services.google_ads.api_token', ''));
        $customerId = (string) ($credentials['customer_id'] ?? config('services.google_ads.customer_id', ''));
        $developerToken = (string) ($credentials['developer_token'] ?? config('services.google_ads.developer_token', ''));

        if ($apiToken === '' || $customerId === '' || $developerToken === '') {
            return [];
        }

        try {
            $response = Http::withToken($apiToken)
                ->withHeaders([
                    'developer-token' => $developerToken,
                    'Content-Type' => 'application/json',
                ])
                ->timeout(25)
                ->post("https://googleads.googleapis.com/v18/customers/{$customerId}/googleAds:searchStream", [
                    'query' => sprintf(
                        "SELECT campaign.id, campaign.name, metrics.cost_micros, metrics.clicks, metrics.impressions, metrics.conversions, segments.date FROM campaign WHERE segments.date BETWEEN '%s' AND '%s'",
                        $from->toDateString(),
                        $to->toDateString()
                    ),
                ]);

            if (! $response->successful()) {
                return [];
            }

            $payload = $response->json();
            $rows = [];

            foreach ((array) $payload as $chunk) {
                foreach ((array) ($chunk['results'] ?? []) as $result) {
                    $campaignId = (string) data_get($result, 'campaign.id', '');
                    if ($campaignId === '') {
                        continue;
                    }

                    $costMicros = (int) data_get($result, 'metrics.cost_micros', 0);

                    $rows[] = [
                        'campaign_id' => $campaignId,
                        'campaign_name' => (string) data_get($result, 'campaign.name', ''),
                        'snapshot_date' => (string) data_get($result, 'segments.date', $to->toDateString()),
                        'spend' => round($costMicros / 1000000, 2),
                        'clicks' => (int) data_get($result, 'metrics.clicks', 0),
                        'impressions' => (int) data_get($result, 'metrics.impressions', 0),
                        'conversions' => (int) round((float) data_get($result, 'metrics.conversions', 0)),
                    ];
                }
            }

            return $rows;
        } catch (Throwable) {
            return [];
        }
    }
}

