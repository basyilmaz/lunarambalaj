<?php

namespace App\Services\Ads\Concerns;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use RuntimeException;

trait UsesGoogleAdsCredentials
{
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
