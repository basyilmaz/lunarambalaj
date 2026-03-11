<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schedule;
use Illuminate\Support\Facades\Schema;
use App\Jobs\ExportOfflineConversionsJob;
use App\Jobs\SyncAdsCampaignSnapshotsJob;
use App\Services\Ads\AdsSyncService;
use App\Services\Ads\OfflineConversionExportService;
use Carbon\CarbonImmutable;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('ops:db-health {--expect-seeded : Validate minimum seed counts} {--json : Emit JSON output}', function () {
    $requiredTables = [
        'migrations',
        'languages',
        'settings',
        'pages',
        'page_translations',
        'service_items',
        'service_item_translations',
        'product_categories',
        'product_category_translations',
        'products',
        'product_translations',
        'posts',
        'post_translations',
        'faqs',
        'faq_translations',
        'references',
        'leads',
    ];

    $checks = [];
    $pushCheck = function (string $key, bool $ok, string $message, array $meta = []) use (&$checks): void {
        $checks[] = [
            'key' => $key,
            'ok' => $ok,
            'message' => $message,
            'meta' => $meta,
        ];
    };

    $connectionOk = true;
    try {
        DB::connection()->getPdo();
        $pushCheck('connection', true, 'Database connection established.');
    } catch (\Throwable $e) {
        $connectionOk = false;
        $pushCheck('connection', false, 'Database connection failed.', ['error' => $e->getMessage()]);
    }

    $missingTables = [];
    if ($connectionOk) {
        try {
            foreach ($requiredTables as $table) {
                if (!Schema::hasTable($table)) {
                    $missingTables[] = $table;
                }
            }

            $pushCheck(
                'required_tables',
                count($missingTables) === 0,
                count($missingTables) === 0 ? 'All required tables exist.' : 'Missing required tables detected.',
                ['missing' => $missingTables]
            );
        } catch (\Throwable $e) {
            $pushCheck('required_tables', false, 'Required table checks failed.', ['error' => $e->getMessage()]);
        }
    } else {
        $pushCheck(
            'required_tables',
            false,
            'Required table checks skipped because DB connection failed.',
            ['missing' => $requiredTables]
        );
    }

    if ($this->option('expect-seeded') && $connectionOk && count($missingTables) === 0) {
        $seedBaselines = [
            'languages' => 4,
            'settings' => 1,
            'pages' => 5,
            'product_categories' => 6,
            'products' => 10,
        ];

        $insufficient = [];
        foreach ($seedBaselines as $table => $minCount) {
            $count = (int) DB::table($table)->count();
            if ($count < $minCount) {
                $insufficient[] = [
                    'table' => $table,
                    'count' => $count,
                    'min' => $minCount,
                ];
            }
        }

        $pushCheck(
            'seed_baseline',
            count($insufficient) === 0,
            count($insufficient) === 0 ? 'Seed baseline requirements satisfied.' : 'Seed baseline requirements failed.',
            ['insufficient' => $insufficient]
        );
    } elseif ($this->option('expect-seeded') && !$connectionOk) {
        $pushCheck(
            'seed_baseline',
            false,
            'Seed baseline check skipped because DB connection failed.',
            ['reason' => 'connection_failed']
        );
    } elseif ($this->option('expect-seeded') && count($missingTables) > 0) {
        $pushCheck(
            'seed_baseline',
            false,
            'Seed baseline check skipped because required tables are missing.',
            ['missing' => $missingTables]
        );
    }

    $failedChecks = array_values(array_filter($checks, fn (array $check): bool => !$check['ok']));
    $ok = count($failedChecks) === 0;

    $payload = [
        'ok' => $ok,
        'failed_count' => count($failedChecks),
        'checks' => $checks,
        'timestamp' => now()->toIso8601String(),
    ];

    if ($this->option('json')) {
        $this->line(json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
    } else {
        foreach ($checks as $check) {
            $prefix = $check['ok'] ? 'OK' : 'FAIL';
            $this->line(sprintf('[%s] %s - %s', $prefix, $check['key'], $check['message']));

            if (!$check['ok'] && !empty($check['meta'])) {
                $this->line('  ' . json_encode($check['meta'], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
            }
        }
    }

    return $ok ? 0 : 1;
})->purpose('Validate DB schema and seed readiness');

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
