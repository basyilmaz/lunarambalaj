<?php

namespace App\Services\Ads\Clients;

use App\Models\AdIntegration;
use App\Services\Ads\Contracts\AdsPlatformClientInterface;
use Carbon\CarbonInterface;
use Illuminate\Support\Facades\Http;
use Throwable;

class MetaAdsClient implements AdsPlatformClientInterface
{
    public function fetchCampaignSnapshots(AdIntegration $integration, CarbonInterface $from, CarbonInterface $to): array
    {
        $credentials = $integration->credentials ?? [];
        $accessToken = (string) ($credentials['access_token'] ?? config('services.meta_ads.access_token', ''));
        $adAccountId = (string) ($credentials['ad_account_id'] ?? config('services.meta_ads.ad_account_id', ''));

        if ($accessToken === '' || $adAccountId === '') {
            return [];
        }

        try {
            $response = Http::timeout(25)->get("https://graph.facebook.com/v20.0/act_{$adAccountId}/insights", [
                'access_token' => $accessToken,
                'fields' => 'campaign_id,campaign_name,spend,clicks,impressions,actions,date_start',
                'level' => 'campaign',
                'time_increment' => 1,
                'time_range' => json_encode([
                    'since' => $from->toDateString(),
                    'until' => $to->toDateString(),
                ]),
            ]);

            if (! $response->successful()) {
                return [];
            }

            $rows = [];
            foreach ((array) data_get($response->json(), 'data', []) as $item) {
                $campaignId = (string) ($item['campaign_id'] ?? '');
                if ($campaignId === '') {
                    continue;
                }

                $conversions = 0;
                foreach ((array) ($item['actions'] ?? []) as $action) {
                    if (in_array((string) ($action['action_type'] ?? ''), ['lead', 'onsite_conversion.lead_grouped'], true)) {
                        $conversions += (int) round((float) ($action['value'] ?? 0));
                    }
                }

                $rows[] = [
                    'campaign_id' => $campaignId,
                    'campaign_name' => (string) ($item['campaign_name'] ?? ''),
                    'snapshot_date' => (string) ($item['date_start'] ?? $to->toDateString()),
                    'spend' => (float) ($item['spend'] ?? 0),
                    'clicks' => (int) ($item['clicks'] ?? 0),
                    'impressions' => (int) ($item['impressions'] ?? 0),
                    'conversions' => $conversions,
                ];
            }

            return $rows;
        } catch (Throwable) {
            return [];
        }
    }
}

