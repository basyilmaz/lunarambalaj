<?php

namespace Tests\Feature;

use App\Models\AdIntegration;
use App\Models\CampaignSnapshot;
use App\Services\Ads\AdsClientFactory;
use App\Services\Ads\AdsSyncService;
use App\Services\Ads\Contracts\AdsPlatformClientInterface;
use Carbon\CarbonImmutable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdsSyncServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_sync_service_upserts_campaign_snapshots_and_updates_last_sync(): void
    {
        $integration = AdIntegration::query()->create([
            'platform' => 'google_ads',
            'name' => 'Google Ads',
            'credentials' => ['api_token' => 'x', 'customer_id' => '123', 'developer_token' => 'y'],
            'is_active' => true,
        ]);

        $fakeClient = new class implements AdsPlatformClientInterface
        {
            public function fetchCampaignSnapshots(AdIntegration $integration, \Carbon\CarbonInterface $from, \Carbon\CarbonInterface $to): array
            {
                return [
                    [
                        'campaign_id' => 'cmp-1',
                        'campaign_name' => 'Brand Search',
                        'snapshot_date' => $from->toDateString(),
                        'spend' => 1250.40,
                        'clicks' => 300,
                        'impressions' => 15000,
                        'conversions' => 12,
                    ],
                ];
            }
        };

        $factory = $this->createMock(AdsClientFactory::class);
        $factory->method('make')->with('google_ads')->willReturn($fakeClient);

        $service = new AdsSyncService($factory);
        $result = $service->syncPlatform(
            'google_ads',
            CarbonImmutable::parse('2026-02-01')->startOfDay(),
            CarbonImmutable::parse('2026-02-02')->endOfDay()
        );

        $this->assertSame('google_ads', $result['platform']);
        $this->assertSame(1, $result['fetched']);
        $this->assertSame(1, $result['upserted']);

        $this->assertDatabaseHas('campaign_snapshots', [
            'platform' => 'google_ads',
            'campaign_id' => 'cmp-1',
            'campaign_name' => 'Brand Search',
            'clicks' => 300,
            'impressions' => 15000,
            'conversions' => 12,
        ]);

        $integration->refresh();
        $this->assertNotNull($integration->last_sync_at);
        $this->assertSame('success', $integration->last_sync_status);
        $this->assertNull($integration->last_sync_error);

        $this->assertSame(1, CampaignSnapshot::query()->count());
        $this->assertDatabaseHas('ads_sync_logs', [
            'platform' => 'google_ads',
            'status' => 'success',
            'fetched' => 1,
            'upserted' => 1,
        ]);
    }

    public function test_sync_service_logs_skipped_when_integration_not_active(): void
    {
        $factory = $this->createMock(AdsClientFactory::class);
        $service = new AdsSyncService($factory);

        $result = $service->syncPlatform(
            'meta_ads',
            CarbonImmutable::parse('2026-02-01')->startOfDay(),
            CarbonImmutable::parse('2026-02-02')->endOfDay()
        );

        $this->assertSame('meta_ads', $result['platform']);
        $this->assertSame(0, $result['fetched']);
        $this->assertSame(0, $result['upserted']);

        $this->assertDatabaseHas('ads_sync_logs', [
            'platform' => 'meta_ads',
            'status' => 'skipped',
        ]);
    }
}
