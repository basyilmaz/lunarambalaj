<?php

namespace Tests\Feature;

use App\Models\AdIntegration;
use App\Models\AttributionLog;
use App\Models\ConversionMapping;
use App\Models\EventLog;
use App\Models\Lead;
use App\Models\TrackingEvent;
use App\Services\Ads\UploadClickConversionsService;
use Carbon\CarbonImmutable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class UploadClickConversionsServiceTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Cache::flush();

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

    public function test_uploads_won_deal_events_with_gclid_to_google_ads(): void
    {
        AdIntegration::query()->create([
            'platform' => 'google_ads',
            'name' => 'Google Ads',
            'credentials' => [
                'client_id' => 'cid',
                'client_secret' => 'csecret',
                'refresh_token' => 'rtok',
                'developer_token' => 'devtok',
                'customer_id' => '1234567890',
                'login_customer_id' => '9999999999',
            ],
            'is_active' => true,
        ]);

        $trackingEvent = TrackingEvent::query()->create([
            'event_key' => 'won_deal',
            'display_name' => 'Won Deal',
            'is_active' => true,
        ]);

        ConversionMapping::query()->create([
            'platform' => 'google_ads',
            'tracking_event_id' => $trackingEvent->id,
            'remote_conversion_id' => '7700000000',
            'value_rule' => 'estimated_value',
            'dedup_key' => 'lead_id',
            'is_active' => true,
        ]);

        $lead = Lead::query()->create([
            'type' => 'quote',
            'name' => 'Test Lead',
            'phone' => '+905551112233',
            'email' => 'test@example.com',
            'gclid' => 'Cj0ABCDEF',
            'estimated_value' => 7500.00,
            'meta' => ['locale' => 'tr'],
            'created_at' => CarbonImmutable::parse('2026-05-10 12:00:00'),
        ]);

        AttributionLog::query()->create([
            'lead_id' => $lead->id,
            'gclid' => 'Cj0ABCDEF',
            'utm_source' => 'google',
            'session_id' => 'sess',
        ]);

        EventLog::query()->create([
            'event_key' => 'won_deal',
            'lead_id' => $lead->id,
            'session_id' => null,
            'page_path' => '/admin',
            'locale' => 'tr',
            'payload' => ['source' => 'backoffice'],
            'created_at' => CarbonImmutable::parse('2026-05-12 14:00:00'),
            'updated_at' => CarbonImmutable::parse('2026-05-12 14:00:00'),
        ]);

        Http::fake([
            'oauth2.googleapis.com/token' => Http::response(['access_token' => 'ya29.x'], 200),
            'googleads.googleapis.com/*' => Http::response([
                'results' => [
                    ['gclid' => 'Cj0ABCDEF', 'conversionDateTime' => '2026-05-12 14:00:00+00:00'],
                ],
            ], 200),
        ]);

        $result = (new UploadClickConversionsService())->uploadForRange(
            CarbonImmutable::parse('2026-05-01'),
            CarbonImmutable::parse('2026-05-31')
        );

        $this->assertSame('success', $result['status']);
        $this->assertSame(1, $result['sent']);

        Http::assertSent(function ($request) use ($lead) {
            if (! str_contains($request->url(), 'uploadClickConversions')) {
                return false;
            }
            $conv = $request->data()['conversions'][0] ?? null;
            return $conv
                && $conv['gclid'] === 'Cj0ABCDEF'
                && $conv['conversionAction'] === 'customers/1234567890/conversionActions/7700000000'
                && (float) $conv['conversionValue'] === 7500.0
                && $conv['currencyCode'] === 'TRY'
                && $conv['orderId'] === 'lead-' . $lead->id
                && $request->header('developer-token')[0] === 'devtok'
                && $request->header('login-customer-id')[0] === '9999999999';
        });
    }

    public function test_skips_events_without_gclid(): void
    {
        AdIntegration::query()->create([
            'platform' => 'google_ads',
            'name' => 'Google Ads',
            'credentials' => [
                'client_id' => 'cid',
                'client_secret' => 'csecret',
                'refresh_token' => 'rtok',
                'developer_token' => 'devtok',
                'customer_id' => '1234567890',
            ],
            'is_active' => true,
        ]);

        $trackingEvent = TrackingEvent::query()->create([
            'event_key' => 'won_deal',
            'display_name' => 'Won Deal',
            'is_active' => true,
        ]);

        ConversionMapping::query()->create([
            'platform' => 'google_ads',
            'tracking_event_id' => $trackingEvent->id,
            'remote_conversion_id' => '7700000000',
            'value_rule' => 'lead',
            'is_active' => true,
        ]);

        $lead = Lead::query()->create([
            'type' => 'quote',
            'name' => 'No GCLID',
            'phone' => '+90555',
            'email' => 'x@y.com',
            'gclid' => null,
            'meta' => [],
            'created_at' => CarbonImmutable::parse('2026-05-10'),
        ]);

        EventLog::query()->create([
            'event_key' => 'won_deal',
            'lead_id' => $lead->id,
            'page_path' => '/admin',
            'locale' => 'tr',
            'payload' => [],
            'created_at' => CarbonImmutable::parse('2026-05-12'),
            'updated_at' => CarbonImmutable::parse('2026-05-12'),
        ]);

        Http::fake();

        $result = (new UploadClickConversionsService())->uploadForRange(
            CarbonImmutable::parse('2026-05-01'),
            CarbonImmutable::parse('2026-05-31')
        );

        $this->assertSame('skipped', $result['status']);
        $this->assertSame(0, $result['sent']);
        Http::assertNothingSent();
    }

    public function test_returns_skipped_when_no_active_mapping(): void
    {
        AdIntegration::query()->create([
            'platform' => 'google_ads',
            'name' => 'Google Ads',
            'credentials' => ['customer_id' => '111', 'developer_token' => 'd', 'client_id' => 'a', 'client_secret' => 'b', 'refresh_token' => 'c'],
            'is_active' => true,
        ]);

        Http::fake();

        $result = (new UploadClickConversionsService())->uploadForRange(
            CarbonImmutable::parse('2026-05-01'),
            CarbonImmutable::parse('2026-05-31')
        );

        $this->assertSame('skipped', $result['status']);
        $this->assertStringContainsString('mapping', $result['message']);
    }
}
