<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use App\Jobs\ExportOfflineConversionsJob;
use App\Jobs\SyncAdsCampaignSnapshotsJob;
use App\Services\Ads\AdsSyncService;
use App\Services\Ads\OfflineConversionExportService;
use Carbon\CarbonImmutable;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('ads:sync-campaign-snapshots {--platform=} {--from=} {--to=} {--queue}', function (AdsSyncService $syncService) {
    $platformOption = (string) ($this->option('platform') ?? '');
    $platforms = $platformOption !== '' ? [$platformOption] : ['google_ads', 'meta_ads'];
    $from = CarbonImmutable::parse((string) ($this->option('from') ?: now()->subDay()->toDateString()))->startOfDay();
    $to = CarbonImmutable::parse((string) ($this->option('to') ?: now()->toDateString()))->endOfDay();

    foreach ($platforms as $platform) {
        if ($this->option('queue')) {
            SyncAdsCampaignSnapshotsJob::dispatch($platform, $from->toDateString(), $to->toDateString());
            $this->info("Queued sync: {$platform} ({$from->toDateString()} - {$to->toDateString()})");
            continue;
        }

        $result = $syncService->syncPlatform($platform, $from, $to);
        $this->info(sprintf(
            'Synced %s | fetched=%d upserted=%d',
            $result['platform'],
            $result['fetched'],
            $result['upserted']
        ));
    }
})->purpose('Sync campaign snapshot metrics from Google Ads and Meta Ads');

Artisan::command('ads:export-offline-conversions {--platform=} {--from=} {--to=} {--queue}', function (OfflineConversionExportService $exportService) {
    $platformOption = (string) ($this->option('platform') ?? '');
    $platforms = $platformOption !== '' ? [$platformOption] : ['google_ads', 'meta_ads'];
    $from = CarbonImmutable::parse((string) ($this->option('from') ?: now()->subDay()->toDateString()))->startOfDay();
    $to = CarbonImmutable::parse((string) ($this->option('to') ?: now()->toDateString()))->endOfDay();

    foreach ($platforms as $platform) {
        if ($this->option('queue')) {
            ExportOfflineConversionsJob::dispatch($platform, $from->toDateString(), $to->toDateString());
            $this->info("Queued export: {$platform} ({$from->toDateString()} - {$to->toDateString()})");
            continue;
        }

        $result = $exportService->exportPlatform($platform, $from, $to);
        $this->info(sprintf(
            'Export %s | status=%s rows=%d file=%s',
            $result['platform'],
            $result['status'],
            $result['rows'],
            (string) ($result['file_path'] ?? '-')
        ));
    }
})->purpose('Export offline conversion CSV for Google Ads / Meta Ads mappings');

Schedule::command('ads:sync-campaign-snapshots --queue')
    ->hourly()
    ->withoutOverlapping();

Schedule::command('ads:export-offline-conversions --queue')
    ->dailyAt('03:30')
    ->withoutOverlapping();
