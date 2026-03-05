<?php

namespace App\Services\Ads;

use App\Models\AdsSyncLog;
use App\Models\AdIntegration;
use App\Models\CampaignSnapshot;
use Carbon\CarbonImmutable;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Throwable;

class AdsSyncService
{
    public function __construct(protected AdsClientFactory $clientFactory)
    {
    }

    /**
     * @return array{platform:string,fetched:int,upserted:int}
     */
    public function syncPlatform(string $platform, CarbonImmutable $from, CarbonImmutable $to): array
    {
        $integration = AdIntegration::query()
            ->where('platform', $platform)
            ->where('is_active', true)
            ->first();

        if (! $integration) {
            $this->writeSyncLog(
                null,
                $platform,
                'skipped',
                $from,
                $to,
                0,
                0,
                'Active integration not found'
            );

            return ['platform' => $platform, 'fetched' => 0, 'upserted' => 0];
        }

        try {
            $client = $this->clientFactory->make($platform);
        } catch (Throwable $exception) {
            $integration->forceFill([
                'last_sync_status' => 'failed',
                'last_sync_error' => $exception->getMessage(),
            ])->save();

            $this->writeSyncLog(
                $integration,
                $platform,
                'failed',
                $from,
                $to,
                0,
                0,
                $exception->getMessage()
            );

            return ['platform' => $platform, 'fetched' => 0, 'upserted' => 0];
        }

        $rows = collect($client->fetchCampaignSnapshots($integration, $from, $to))
            ->filter(fn ($row): bool => is_array($row) && ! empty($row['campaign_id']))
            ->map(function (array $row) use ($platform): array {
                return [
                    'platform' => $platform,
                    'campaign_id' => (string) $row['campaign_id'],
                    'campaign_name' => (string) ($row['campaign_name'] ?? ''),
                    'snapshot_date' => (string) ($row['snapshot_date'] ?? now()->toDateString()),
                    'spend' => (float) ($row['spend'] ?? 0),
                    'clicks' => (int) ($row['clicks'] ?? 0),
                    'impressions' => (int) ($row['impressions'] ?? 0),
                    'conversions' => (int) ($row['conversions'] ?? 0),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            });

        $upserted = $this->upsertSnapshots($rows);

        $integration->forceFill([
            'last_sync_at' => now(),
            'last_sync_status' => 'success',
            'last_sync_error' => null,
        ])->save();

        $this->writeSyncLog(
            $integration,
            $platform,
            'success',
            $from,
            $to,
            $rows->count(),
            $upserted,
            null
        );

        return [
            'platform' => $platform,
            'fetched' => $rows->count(),
            'upserted' => $upserted,
        ];
    }

    /**
     * @param  Collection<int, array<string, mixed>>  $rows
     */
    protected function upsertSnapshots(Collection $rows): int
    {
        if ($rows->isEmpty()) {
            return 0;
        }

        DB::transaction(function () use ($rows): void {
            CampaignSnapshot::query()->upsert(
                $rows->values()->all(),
                ['platform', 'campaign_id', 'snapshot_date'],
                ['campaign_name', 'spend', 'clicks', 'impressions', 'conversions', 'updated_at']
            );
        });

        return $rows->count();
    }

    protected function writeSyncLog(
        ?AdIntegration $integration,
        string $platform,
        string $status,
        CarbonImmutable $from,
        CarbonImmutable $to,
        int $fetched,
        int $upserted,
        ?string $errorMessage
    ): void {
        AdsSyncLog::query()->create([
            'ad_integration_id' => $integration?->id,
            'platform' => $platform,
            'status' => $status,
            'from_date' => $from->toDateString(),
            'to_date' => $to->toDateString(),
            'fetched' => $fetched,
            'upserted' => $upserted,
            'error_message' => $errorMessage,
            'context' => [
                'integration_active' => $integration?->is_active,
            ],
            'created_at' => now(),
        ]);
    }
}
