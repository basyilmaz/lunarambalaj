<?php

namespace App\Services\Ads;

use App\Models\AdIntegration;
use App\Models\ConversionMapping;
use App\Models\EventLog;
use App\Services\Ads\Concerns\UsesGoogleAdsCredentials;
use Carbon\CarbonImmutable;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class UploadClickConversionsService
{
    use UsesGoogleAdsCredentials;

    /**
     * @return array{platform:string,status:string,sent:int,received:int,failed:int,message:string}
     */
    public function uploadForRange(CarbonImmutable $from, CarbonImmutable $to): array
    {
        $integration = AdIntegration::query()
            ->where('platform', 'google_ads')
            ->where('is_active', true)
            ->first();

        if (! $integration) {
            return $this->result('skipped', 0, 0, 0, 'Active Google Ads integration not found');
        }

        $credentials = $integration->credentials ?? [];
        $customerId = $this->credential($credentials, 'customer_id');
        $developerToken = $this->credential($credentials, 'developer_token');
        $loginCustomerId = $this->credential($credentials, 'login_customer_id');

        if ($customerId === '' || $developerToken === '') {
            return $this->result('failed', 0, 0, 0, 'customer_id and developer_token required');
        }

        $mappings = ConversionMapping::query()
            ->with('trackingEvent')
            ->where('platform', 'google_ads')
            ->where('is_active', true)
            ->get();

        $mappingByEvent = $mappings
            ->filter(fn (ConversionMapping $m): bool => $m->trackingEvent !== null && trim((string) $m->remote_conversion_id) !== '')
            ->keyBy(fn (ConversionMapping $m): string => (string) $m->trackingEvent?->event_key);

        if ($mappingByEvent->isEmpty()) {
            return $this->result('skipped', 0, 0, 0, 'No active mappings with remote_conversion_id configured');
        }

        $events = EventLog::query()
            ->with(['lead.attributionLogs' => fn ($q) => $q->latest('id')])
            ->whereIn('event_key', $mappingByEvent->keys()->all())
            ->whereBetween('created_at', [$from, $to])
            ->orderBy('id')
            ->get();

        $clickConversions = $this->buildClickConversions($events, $mappingByEvent, $customerId);

        if ($clickConversions->isEmpty()) {
            return $this->result('skipped', 0, 0, 0, 'No eligible events with gclid in range');
        }

        $accessToken = $this->resolveAccessToken($credentials);

        $response = Http::withToken($accessToken)
            ->withHeaders(array_filter([
                'developer-token' => $developerToken,
                'login-customer-id' => $loginCustomerId !== '' ? $loginCustomerId : null,
                'Content-Type' => 'application/json',
            ]))
            ->timeout(30)
            ->post("https://googleads.googleapis.com/v20/customers/{$customerId}:uploadClickConversions", [
                'conversions' => $clickConversions->all(),
                'partialFailure' => true,
            ]);

        if (! $response->successful()) {
            throw new RuntimeException(sprintf(
                'Google Ads uploadClickConversions error: HTTP %d %s',
                $response->status(),
                substr((string) $response->body(), 0, 500)
            ));
        }

        $payload = $response->json();
        $results = (array) ($payload['results'] ?? []);
        $partialFailure = $payload['partialFailureError'] ?? null;
        $failed = $partialFailure ? (int) ($partialFailure['details'][0]['errors'] ? count($partialFailure['details'][0]['errors']) : 1) : 0;
        $received = count(array_filter($results, fn ($r) => ! empty($r['gclid'] ?? null) || ! empty($r['conversionDateTime'] ?? null)));

        return $this->result(
            'success',
            $clickConversions->count(),
            $received,
            $failed,
            $partialFailure ? 'Partial failures returned by Google Ads' : 'Click conversions uploaded'
        );
    }

    /**
     * @param  Collection<int, EventLog>  $events
     * @param  Collection<string, ConversionMapping>  $mappingByEvent
     * @return Collection<int, array<string, mixed>>
     */
    protected function buildClickConversions(Collection $events, Collection $mappingByEvent, string $customerId): Collection
    {
        $rows = collect();

        foreach ($events as $event) {
            $mapping = $mappingByEvent->get($event->event_key);
            if (! $mapping || ! $event->lead) {
                continue;
            }

            $attribution = $event->lead->attributionLogs->first();
            $gclid = trim((string) ($attribution?->gclid ?? $event->lead->gclid ?? ''));
            if ($gclid === '') {
                continue;
            }

            $conversionTime = optional($event->created_at)
                ->setTimezone('UTC')
                ->format('Y-m-d H:i:sP');

            $value = $this->resolveConversionValue($mapping->value_rule, $event);
            $dedupId = $this->resolveDedupId($mapping->dedup_key, $event);

            $rows->push(array_filter([
                'conversionAction' => sprintf(
                    'customers/%s/conversionActions/%s',
                    $customerId,
                    $mapping->remote_conversion_id
                ),
                'gclid' => $gclid,
                'conversionDateTime' => $conversionTime,
                'conversionValue' => $value,
                'currencyCode' => 'TRY',
                'orderId' => $dedupId ?: null,
            ], fn ($v) => $v !== null));
        }

        return $rows;
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

        if (strcasecmp($rule, 'estimated_value') === 0) {
            $estimated = $event->lead?->estimated_value;
            if ($estimated !== null) {
                return (float) $estimated;
            }
        }

        if (strcasecmp($rule, 'quantity') === 0) {
            $quantity = (int) data_get($event->lead?->meta, 'quantity', 1);

            return (float) max(1, $quantity);
        }

        if (str_starts_with($rule, 'meta.')) {
            $metaValue = data_get($event->lead?->meta, substr($rule, 5));
            if (is_numeric($metaValue)) {
                return (float) $metaValue;
            }
        }

        return 1.0;
    }

    protected function resolveDedupId(?string $rule, EventLog $event): string
    {
        $rule = trim((string) ($rule ?? ''));

        return match (true) {
            $rule === '', strcasecmp($rule, 'event_id') === 0 => 'event-' . $event->id,
            strcasecmp($rule, 'lead_id') === 0 => 'lead-' . ($event->lead?->id ?? $event->lead_id ?? $event->id),
            default => $rule . '-' . $event->id,
        };
    }

    /**
     * @return array{platform:string,status:string,sent:int,received:int,failed:int,message:string}
     */
    protected function result(string $status, int $sent, int $received, int $failed, string $message): array
    {
        return [
            'platform' => 'google_ads',
            'status' => $status,
            'sent' => $sent,
            'received' => $received,
            'failed' => $failed,
            'message' => $message,
        ];
    }
}
