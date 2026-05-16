<?php

namespace App\Support;

use App\Models\EventLog;
use App\Models\Lead;
use App\Models\TrackingEvent;

class LeadConversion
{
    /**
     * Record a back-office conversion event for a lead so the next
     * ads:upload-click-conversions run can push it to Google Ads.
     *
     * @param  array<string, mixed>  $extraPayload
     */
    public static function recordEvent(Lead $lead, string $eventKey, array $extraPayload = []): ?EventLog
    {
        $trackingEvent = TrackingEvent::query()->firstOrCreate(
            ['event_key' => $eventKey],
            [
                'display_name' => match ($eventKey) {
                    'qualified_lead' => 'Qualified Lead',
                    'won_deal' => 'Won Deal',
                    default => ucwords(str_replace('_', ' ', $eventKey)),
                },
                'is_active' => true,
            ]
        );

        if (! $trackingEvent->is_active) {
            return null;
        }

        $payload = array_merge([
            'lead_id' => $lead->id,
            'lead_type' => $lead->type,
            'lead_status' => $lead->status,
            'estimated_value' => $lead->estimated_value !== null ? (float) $lead->estimated_value : null,
            'gclid' => $lead->gclid,
            'product_category' => data_get($lead->meta, 'product_category'),
            'recorded_at' => now()->toIso8601String(),
            'source' => 'backoffice',
        ], $extraPayload);

        return EventLog::query()->create([
            'event_key' => $eventKey,
            'lead_id' => $lead->id,
            'session_id' => null,
            'page_path' => '/admin',
            'locale' => app()->getLocale(),
            'payload' => $payload,
        ]);
    }
}
