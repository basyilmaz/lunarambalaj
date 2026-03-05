<?php

namespace App\Services\Ads;

use App\Models\ConversionMapping;
use App\Models\EventLog;
use Carbon\CarbonImmutable;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;

class OfflineConversionExportService
{
    /**
     * @return array{platform:string,status:string,rows:int,file_path:?string,message:string}
     */
    public function exportPlatform(string $platform, CarbonImmutable $from, CarbonImmutable $to): array
    {
        $mappings = ConversionMapping::query()
            ->with('trackingEvent')
            ->where('platform', $platform)
            ->where('is_active', true)
            ->get();

        if ($mappings->isEmpty()) {
            return [
                'platform' => $platform,
                'status' => 'skipped',
                'rows' => 0,
                'file_path' => null,
                'message' => 'Active conversion mapping not found',
            ];
        }

        $mappingByEvent = $mappings
            ->filter(fn (ConversionMapping $mapping): bool => $mapping->trackingEvent !== null)
            ->keyBy(fn (ConversionMapping $mapping): string => (string) $mapping->trackingEvent?->event_key);

        if ($mappingByEvent->isEmpty()) {
            return [
                'platform' => $platform,
                'status' => 'skipped',
                'rows' => 0,
                'file_path' => null,
                'message' => 'Mapped tracking events not found',
            ];
        }

        $events = EventLog::query()
            ->with(['lead.attributionLogs' => fn ($query) => $query->latest('id')])
            ->whereIn('event_key', $mappingByEvent->keys()->all())
            ->whereBetween('created_at', [$from, $to])
            ->orderBy('id')
            ->get();

        $rows = $this->buildRows($platform, $events, $mappingByEvent);

        if ($rows->isEmpty()) {
            return [
                'platform' => $platform,
                'status' => 'skipped',
                'rows' => 0,
                'file_path' => null,
                'message' => 'No eligible conversion rows for selected range',
            ];
        }

        $filePath = $this->writeCsv($platform, $from, $to, $rows);

        return [
            'platform' => $platform,
            'status' => 'success',
            'rows' => $rows->count(),
            'file_path' => $filePath,
            'message' => 'Offline conversion export created',
        ];
    }

    /**
     * @param  Collection<int, EventLog>  $events
     * @param  Collection<string, ConversionMapping>  $mappingByEvent
     * @return Collection<int, array<string, mixed>>
     */
    protected function buildRows(string $platform, Collection $events, Collection $mappingByEvent): Collection
    {
        $rows = collect();

        foreach ($events as $event) {
            $mapping = $mappingByEvent->get($event->event_key);
            if (! $mapping || ! $event->lead) {
                continue;
            }

            $attribution = $event->lead->attributionLogs->first();
            $value = $this->resolveConversionValue($mapping->value_rule, $event);
            $dedupId = $this->resolveDedupId($mapping->dedup_key, $event);

            if ($platform === 'google_ads') {
                $gclid = trim((string) ($attribution?->gclid ?? ''));
                if ($gclid === '') {
                    continue;
                }

                $rows->push([
                    'conversion_action' => $mapping->remote_conversion_id ?: $event->event_key,
                    'gclid' => $gclid,
                    'conversion_time' => optional($event->created_at)->setTimezone('UTC')->format('Y-m-d H:i:sP'),
                    'conversion_value' => $value,
                    'currency_code' => 'TRY',
                    'order_id' => $dedupId,
                ]);

                continue;
            }

            if ($platform === 'meta_ads') {
                $fbclid = trim((string) ($attribution?->fbclid ?? ''));
                if ($fbclid === '') {
                    continue;
                }

                $rows->push([
                    'event_name' => $mapping->remote_conversion_id ?: $event->event_key,
                    'event_time' => optional($event->created_at)->timestamp,
                    'action_source' => 'website',
                    'fbclid' => $fbclid,
                    'event_id' => $dedupId,
                    'value' => $value,
                    'currency' => 'TRY',
                ]);
            }
        }

        return $rows;
    }

    /**
     * @param  Collection<int, array<string, mixed>>  $rows
     */
    protected function writeCsv(string $platform, CarbonImmutable $from, CarbonImmutable $to, Collection $rows): string
    {
        $dir = 'offline-conversions';
        $disk = Storage::disk('local');
        $disk->makeDirectory($dir);

        $filename = sprintf(
            '%s/%s-%s-%s-%s.csv',
            $dir,
            $platform,
            $from->toDateString(),
            $to->toDateString(),
            now()->format('YmdHis')
        );

        $handle = fopen('php://temp', 'w+');

        $header = $platform === 'google_ads'
            ? ['conversion_action', 'gclid', 'conversion_time', 'conversion_value', 'currency_code', 'order_id']
            : ['event_name', 'event_time', 'action_source', 'fbclid', 'event_id', 'value', 'currency'];

        fputcsv($handle, $header);
        foreach ($rows as $row) {
            fputcsv($handle, array_values($row));
        }

        rewind($handle);
        $content = stream_get_contents($handle);
        fclose($handle);

        $disk->put($filename, (string) $content);

        return $filename;
    }

    protected function resolveConversionValue(?string $rule, EventLog $event): float
    {
        $rule = trim((string) ($rule ?? ''));
        if ($rule === '' || strcasecmp($rule, 'lead') === 0) {
            return 1.0;
        }

        if (is_numeric($rule)) {
            return (float) $rule;
        }

        if (strcasecmp($rule, 'quantity') === 0) {
            $quantity = (int) data_get($event->lead?->meta, 'quantity', 1);

            return (float) max(1, $quantity);
        }

        if (str_starts_with($rule, 'meta.')) {
            $metaKey = substr($rule, 5);
            $metaValue = data_get($event->lead?->meta, $metaKey, null);

            if (is_numeric($metaValue)) {
                return (float) $metaValue;
            }
        }

        return 1.0;
    }

    protected function resolveDedupId(?string $template, EventLog $event): string
    {
        $template = trim((string) ($template ?? ''));
        if ($template === '') {
            return 'event-' . $event->id;
        }

        return strtr($template, [
            '{event_id}' => (string) $event->id,
            '{lead_id}' => (string) ($event->lead_id ?? 0),
            '{date}' => optional($event->created_at)->format('YmdHis') ?? now()->format('YmdHis'),
        ]);
    }
}
