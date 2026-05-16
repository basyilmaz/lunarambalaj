<?php

namespace Tests\Feature;

use App\Models\AdIntegration;
use App\Services\Ads\Clients\GoogleAdsClient;
use Carbon\CarbonImmutable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use RuntimeException;
use Tests\TestCase;

class GoogleAdsClientTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Cache::flush();

        // Isolate from any GOOGLE_ADS_* values the developer may have in their shell;
        // every test must drive credentials through the AdIntegration model.
        Config::set('services.google_ads', [
            'api_token' => null,
            'customer_id' => null,
            'login_customer_id' => null,
            'developer_token' => null,
            'client_id' => null,
            'client_secret' => null,
            'refresh_token' => null,
            'conversion_id' => null,
            'labels' => [],
        ]);
    }

    private function integration(array $overrides = []): AdIntegration
    {
        return AdIntegration::query()->create([
            'platform' => 'google_ads',
            'name' => 'Google Ads',
            'credentials' => array_merge([
                'client_id' => 'cid',
                'client_secret' => 'csecret',
                'refresh_token' => 'rtok',
                'developer_token' => 'devtok',
                'customer_id' => '1234567890',
                'login_customer_id' => '9999999999',
            ], $overrides),
            'is_active' => true,
        ]);
    }

    public function test_fetch_campaign_snapshots_with_oauth_returns_normalised_rows(): void
    {
        Http::fake([
            'oauth2.googleapis.com/token' => Http::response([
                'access_token' => 'ya29.test',
                'expires_in' => 3600,
            ], 200),
            'googleads.googleapis.com/*' => Http::response([
                [
                    'results' => [
                        [
                            'campaign' => ['id' => '101', 'name' => 'Brand Search'],
                            'metrics' => [
                                'cost_micros' => 1500000,
                                'clicks' => 42,
                                'impressions' => 1000,
                                'conversions' => 3.0,
                            ],
                            'segments' => ['date' => '2026-05-10'],
                        ],
                    ],
                ],
            ], 200),
        ]);

        $rows = (new GoogleAdsClient())->fetchCampaignSnapshots(
            $this->integration(),
            CarbonImmutable::parse('2026-05-01'),
            CarbonImmutable::parse('2026-05-15')
        );

        $this->assertCount(1, $rows);
        $this->assertSame('101', $rows[0]['campaign_id']);
        $this->assertSame('Brand Search', $rows[0]['campaign_name']);
        $this->assertEqualsWithDelta(1.5, $rows[0]['spend'], 0.001);
        $this->assertSame(42, $rows[0]['clicks']);
        $this->assertSame(3, $rows[0]['conversions']);

        Http::assertSent(function ($request) {
            if (str_starts_with($request->url(), 'https://oauth2.googleapis.com/token')) {
                return $request['grant_type'] === 'refresh_token'
                    && $request['refresh_token'] === 'rtok'
                    && $request['client_id'] === 'cid';
            }
            if (str_contains($request->url(), 'googleads.googleapis.com')) {
                return $request->header('developer-token')[0] === 'devtok'
                    && $request->header('login-customer-id')[0] === '9999999999'
                    && $request->header('Authorization')[0] === 'Bearer ya29.test';
            }
            return false;
        });
    }

    public function test_fetch_campaign_snapshots_throws_on_api_error(): void
    {
        Http::fake([
            'oauth2.googleapis.com/token' => Http::response(['access_token' => 'ya29.x'], 200),
            'googleads.googleapis.com/*' => Http::response('Unauthorized', 401),
        ]);

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Google Ads API error: HTTP 401');

        (new GoogleAdsClient())->fetchCampaignSnapshots(
            $this->integration(),
            CarbonImmutable::parse('2026-05-01'),
            CarbonImmutable::parse('2026-05-15')
        );
    }

    public function test_access_token_is_cached_between_calls(): void
    {
        Http::fake([
            'oauth2.googleapis.com/token' => Http::response(['access_token' => 'ya29.cached'], 200),
            'googleads.googleapis.com/*' => Http::response([['results' => []]], 200),
        ]);

        $client = new GoogleAdsClient();
        $integration = $this->integration();

        $client->fetchCampaignSnapshots($integration, CarbonImmutable::parse('2026-05-01'), CarbonImmutable::parse('2026-05-02'));
        $client->fetchCampaignSnapshots($integration, CarbonImmutable::parse('2026-05-02'), CarbonImmutable::parse('2026-05-03'));

        $tokenCalls = collect(Http::recorded())
            ->filter(fn ($pair) => str_starts_with($pair[0]->url(), 'https://oauth2.googleapis.com/token'))
            ->count();

        $this->assertSame(1, $tokenCalls);
    }

    public function test_missing_credentials_throws_runtime_exception(): void
    {
        Http::fake();

        $integration = AdIntegration::query()->create([
            'platform' => 'google_ads',
            'name' => 'Google Ads',
            'credentials' => ['customer_id' => '', 'developer_token' => ''],
            'is_active' => true,
        ]);

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('credentials incomplete');

        (new GoogleAdsClient())->fetchCampaignSnapshots(
            $integration,
            CarbonImmutable::parse('2026-05-01'),
            CarbonImmutable::parse('2026-05-15')
        );
    }

    public function test_legacy_api_token_still_works_for_backwards_compat(): void
    {
        Http::fake([
            'googleads.googleapis.com/*' => Http::response([['results' => []]], 200),
        ]);

        $integration = AdIntegration::query()->create([
            'platform' => 'google_ads',
            'name' => 'Google Ads',
            'credentials' => [
                'api_token' => 'legacy-token',
                'customer_id' => '111',
                'developer_token' => 'dev',
            ],
            'is_active' => true,
        ]);

        $rows = (new GoogleAdsClient())->fetchCampaignSnapshots(
            $integration,
            CarbonImmutable::parse('2026-05-01'),
            CarbonImmutable::parse('2026-05-02')
        );

        $this->assertSame([], $rows);

        Http::assertSent(function ($request) {
            return str_contains($request->url(), 'googleads.googleapis.com')
                && $request->header('Authorization')[0] === 'Bearer legacy-token';
        });

        // OAuth token endpoint should NOT be hit when api_token is provided.
        Http::assertNotSent(function ($request) {
            return str_starts_with($request->url(), 'https://oauth2.googleapis.com/token');
        });
    }
}
