<?php

namespace Tests\Feature;

use App\Models\AttributionLog;
use App\Models\ConversionMapping;
use App\Models\EventLog;
use App\Models\Lead;
use App\Models\TrackingEvent;
use App\Services\Ads\OfflineConversionExportService;
use Carbon\CarbonImmutable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class OfflineConversionExportServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_google_offline_export_creates_csv_with_rows(): void
    {
        Storage::fake('local');

        $event = TrackingEvent::query()->create([
            'event_key' => 'lead_submit',
            'display_name' => 'Lead Submit',
            'is_active' => true,
        ]);

        ConversionMapping::query()->create([
            'platform' => 'google_ads',
            'tracking_event_id' => $event->id,
            'remote_conversion_id' => 'conv_lead_submit',
            'value_rule' => 'quantity',
            'dedup_key' => 'lead-{lead_id}-event-{event_id}',
            'is_active' => true,
        ]);

        $lead = Lead::query()->create([
            'type' => 'quote',
            'status' => 'new',
            'name' => 'Export Lead',
            'email' => 'export@example.com',
            'meta' => ['quantity' => 7500],
            'created_at' => now(),
        ]);

        AttributionLog::query()->create([
            'lead_id' => $lead->id,
            'utm_source' => 'google',
            'gclid' => 'gclid-123',
            'created_at' => now(),
        ]);

        EventLog::query()->create([
            'event_key' => 'lead_submit',
            'lead_id' => $lead->id,
            'session_id' => 'sess-1',
            'page_path' => '/teklif-al',
            'locale' => 'tr',
            'payload' => ['lead_type' => 'quote'],
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $service = app(OfflineConversionExportService::class);
        $result = $service->exportPlatform(
            'google_ads',
            CarbonImmutable::now()->subDay()->startOfDay(),
            CarbonImmutable::now()->endOfDay()
        );

        $this->assertSame('success', $result['status']);
        $this->assertSame(1, $result['rows']);
        $this->assertNotNull($result['file_path']);
        Storage::disk('local')->assertExists((string) $result['file_path']);

        $csv = Storage::disk('local')->get((string) $result['file_path']);
        $this->assertStringContainsString('conversion_action', $csv);
        $this->assertStringContainsString('gclid-123', $csv);
        $this->assertStringContainsString('conv_lead_submit', $csv);
    }

    public function test_export_skips_when_mapping_is_missing(): void
    {
        Storage::fake('local');

        $service = app(OfflineConversionExportService::class);
        $result = $service->exportPlatform(
            'google_ads',
            CarbonImmutable::now()->subDay()->startOfDay(),
            CarbonImmutable::now()->endOfDay()
        );

        $this->assertSame('skipped', $result['status']);
        $this->assertSame(0, $result['rows']);
        $this->assertNull($result['file_path']);
    }
}

