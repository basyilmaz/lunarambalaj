<?php

namespace App\Services\Ads\Clients;

use App\Models\AdIntegration;
use App\Services\Ads\Concerns\UsesGoogleAdsCredentials;
use App\Services\Ads\Contracts\AdsPlatformClientInterface;
use Carbon\CarbonInterface;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class GoogleAdsClient implements AdsPlatformClientInterface
{
    use UsesGoogleAdsCredentials;

    public function fetchCampaignSnapshots(AdIntegration $integration, CarbonInterface $from, CarbonInterface $to): array
    {
        $credentials = $integration->credentials ?? [];

        $customerId = $this->credential($credentials, 'customer_id');
        $developerToken = $this->credential($credentials, 'developer_token');
        $loginCustomerId = $this->credential($credentials, 'login_customer_id');

        if ($customerId === '' || $developerToken === '') {
            throw new RuntimeException('Google Ads credentials incomplete: customer_id and developer_token required.');
        }

        $accessToken = $this->resolveAccessToken($credentials);

        $response = Http::withToken($accessToken)
            ->withHeaders(array_filter([
                'developer-token' => $developerToken,
                'login-customer-id' => $loginCustomerId !== '' ? $loginCustomerId : null,
                'Content-Type' => 'application/json',
            ]))
            ->timeout(25)
            ->post("https://googleads.googleapis.com/v20/customers/{$customerId}/googleAds:searchStream", [
                'query' => sprintf(
                    "SELECT campaign.id, campaign.name, metrics.cost_micros, metrics.clicks, metrics.impressions, metrics.conversions, segments.date FROM campaign WHERE segments.date BETWEEN '%s' AND '%s'",
                    $from->toDateString(),
                    $to->toDateString()
                ),
            ]);

        if (! $response->successful()) {
            throw new RuntimeException(sprintf(
                'Google Ads API error: HTTP %d %s',
                $response->status(),
                substr((string) $response->body(), 0, 500)
            ));
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
    }
}
