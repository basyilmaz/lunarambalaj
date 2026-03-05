<?php

namespace Tests\Feature;

use App\Services\Ads\OfflineConversionExportService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OfflineConversionExportCommandTest extends TestCase
{
    use RefreshDatabase;

    public function test_offline_conversion_export_command_executes_for_single_platform(): void
    {
        $service = $this->createMock(OfflineConversionExportService::class);
        $service->expects($this->once())
            ->method('exportPlatform')
            ->willReturn([
                'platform' => 'google_ads',
                'status' => 'success',
                'rows' => 3,
                'file_path' => 'offline-conversions/google_ads-2026-03-01-2026-03-02-120000.csv',
                'message' => 'Offline conversion export created',
            ]);

        $this->app->instance(OfflineConversionExportService::class, $service);

        $this->artisan('ads:export-offline-conversions', [
            '--platform' => 'google_ads',
            '--from' => '2026-03-01',
            '--to' => '2026-03-02',
        ])
            ->expectsOutput('Export google_ads | status=success rows=3 file=offline-conversions/google_ads-2026-03-01-2026-03-02-120000.csv')
            ->assertExitCode(0);
    }
}

