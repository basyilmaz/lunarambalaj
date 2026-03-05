<?php

namespace App\Support;

use App\Models\EventLog;
use App\Models\Lead;
use App\Models\TrackingEvent;
use Illuminate\Http\Request;

class TrackingEventLogger
{
    /**
     * @param  array<string, mixed>  $payload
     */
    public function log(
        Request $request,
        string $eventKey,
        array $payload = [],
        ?Lead $lead = null,
        ?string $pagePath = null
    ): ?EventLog
    {
        $isKnownEvent = TrackingEvent::query()
            ->where('event_key', $eventKey)
            ->where('is_active', true)
            ->exists();

        if (! $isKnownEvent) {
            return null;
        }

        return EventLog::query()->create([
            'event_key' => $eventKey,
            'lead_id' => $lead?->id,
            'session_id' => $request->session()->getId(),
            'page_path' => filled($pagePath) ? trim((string) $pagePath) : $request->getPathInfo(),
            'locale' => app()->getLocale(),
            'payload' => $payload,
        ]);
    }
}
