<?php

namespace App\Services\Ads\Clients;

use App\Models\AdIntegration;
use App\Services\Ads\Contracts\AdsPlatformClientInterface;
use Carbon\CarbonInterface;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class GoogleAdsClient implements AdsPlatformClientInterface
{
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
            ->post("https://googleads.googleapis.com/v18/customers/{$customerId}/googleAds:searchStream", [
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

    /**
     * @param  array<string, mixed>  $credentials
     */
    protected function resolveAccessToken(array $credentials): string
    {
        $explicitAccessToken = $this->credential($credentials, 'access_token');
        if ($explicitAccessToken !== '') {
            return $explicitAccessToken;
        }

        $clientId = $this->credential($credentials, 'client_id');
        $clientSecret = $this->credential($credentials, 'client_secret');
        $refreshToken = $this->credential($credentials, 'refresh_token');

        if ($clientId !== '' && $clientSecret !== '' && $refreshToken !== '') {
            return Cache::remember(
                'google_ads:access_token:' . sha1($refreshToken),
                3000,
                fn (): string => $this->refreshAccessToken($clientId, $clientSecret, $refreshToken)
            );
        }

        // Legacy fallback: pre-OAuth integrations stored a long-lived API token.
        $legacyToken = $this->credential($credentials, 'api_token');
        if ($legacyToken !== '') {
            return $legacyToken;
        }

        throw new RuntimeException('Google Ads credentials incomplete: provide client_id/client_secret/refresh_token (preferred) or api_token (legacy).');
    }

    protected function refreshAccessToken(string $clientId, string $clientSecret, string $refreshToken): string
    {
        $response = Http::asForm()
            ->timeout(15)
            ->post('https://oauth2.googleapis.com/token', [
                'client_id' => $clientId,
                'client_secret' => $clientSecret,
                'refresh_token' => $refreshToken,
                'grant_type' => 'refresh_token',
            ]);

        if (! $response->successful()) {
            throw new RuntimeException(sprintf(
                'Google OAuth token refresh failed: HTTP %d %s',
                $response->status(),
                substr((string) $response->body(), 0, 300)
            ));
        }

        $accessToken = (string) ($response->json('access_token') ?? '');
        if ($accessToken === '') {
            throw new RuntimeException('Google OAuth token refresh returned no access_token.');
        }

        return $accessToken;
    }

    /**
     * @param  array<string, mixed>  $credentials
     */
    protected function credential(array $credentials, string $key): string
    {
        $value = $credentials[$key] ?? null;
        if (is_string($value) && trim($value) !== '') {
            return trim($value);
        }

        $configValue = config('services.google_ads.' . $key);
        if (is_string($configValue) && trim($configValue) !== '') {
            return trim($configValue);
        }

        return '';
    }
}
