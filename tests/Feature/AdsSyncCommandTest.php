<?php

namespace Tests\Feature;

use App\Services\Ads\AdsSyncService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdsSyncCommandTest extends TestCase
{
    use RefreshDatabase;

    public function test_ads_sync_command_executes_for_single_platform(): void
    {
        $this->seed();

        $service = $this->createMock(AdsSyncService::class);
        $service->expects($this->once())
            ->method('syncPlatform')
            ->willReturn([
                'platform' => 'google_ads',
                'fetched' => 3,
                'upserted' => 3,
            ]);

        $this->app->instance(AdsSyncService::class, $service);

        $this->artisan('ads:sync-campaign-snapshots', [
            '--platform' => 'google_ads',
            '--from' => '2026-02-01',
            '--to' => '2026-02-02',
        ])
            ->expectsOutput('Synced google_ads | fetched=3 upserted=3')
            ->assertExitCode(0);
    }
}

