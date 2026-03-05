<?php

namespace App\Support;

use App\Models\AttributionLog;
use App\Models\Lead;
use Illuminate\Http\Request;

class AttributionLogger
{
    /**
     * Merge request attribution params with session attribution.
     *
     * @return array<string, mixed>
     */
    public function getAttributionPayload(Request $request): array
    {
        $sessionData = $request->session()->get('attribution', []);
        $lastTouchParams = is_array($sessionData['last_touch']['params'] ?? null) ? $sessionData['last_touch']['params'] : [];
        $firstTouchParams = is_array($sessionData['first_touch']['params'] ?? null) ? $sessionData['first_touch']['params'] : [];

        $payload = [
            'utm_source' => $this->pick($request, 'utm_source', $lastTouchParams, $firstTouchParams, $sessionData),
            'utm_medium' => $this->pick($request, 'utm_medium', $lastTouchParams, $firstTouchParams, $sessionData),
            'utm_campaign' => $this->pick($request, 'utm_campaign', $lastTouchParams, $firstTouchParams, $sessionData),
            'utm_term' => $this->pick($request, 'utm_term', $lastTouchParams, $firstTouchParams, $sessionData),
            'utm_content' => $this->pick($request, 'utm_content', $lastTouchParams, $firstTouchParams, $sessionData),
            'gclid' => $this->pick($request, 'gclid', $lastTouchParams, $firstTouchParams, $sessionData),
            'fbclid' => $this->pick($request, 'fbclid', $lastTouchParams, $firstTouchParams, $sessionData),
            'landing_path' => $sessionData['landing_path'] ?? $request->getPathInfo(),
            'session_id' => $request->session()->getId(),
            'first_touch' => is_array($sessionData['first_touch'] ?? null) ? $sessionData['first_touch'] : null,
            'last_touch' => is_array($sessionData['last_touch'] ?? null) ? $sessionData['last_touch'] : null,
        ];

        return $payload;
    }

    /**
     * Persist lead attribution record.
     *
     * @param  array<string, mixed>  $extraMeta
     */
    public function logLeadAttribution(Lead $lead, Request $request, array $extraMeta = []): AttributionLog
    {
        $payload = $this->getAttributionPayload($request);

        return AttributionLog::query()->create([
            'lead_id' => $lead->id,
            'utm_source' => $payload['utm_source'] ?? null,
            'utm_medium' => $payload['utm_medium'] ?? null,
            'utm_campaign' => $payload['utm_campaign'] ?? null,
            'utm_term' => $payload['utm_term'] ?? null,
            'utm_content' => $payload['utm_content'] ?? null,
            'gclid' => $payload['gclid'] ?? null,
            'fbclid' => $payload['fbclid'] ?? null,
            'landing_path' => $payload['landing_path'] ?? null,
            'session_id' => $payload['session_id'] ?? null,
            'meta' => array_merge([
                'locale' => app()->getLocale(),
                'source_path' => $request->getPathInfo(),
                'referrer' => (string) $request->headers->get('referer'),
                'touch_model' => 'last_touch',
                'first_touch' => $payload['first_touch'] ?? null,
                'last_touch' => $payload['last_touch'] ?? null,
            ], $extraMeta),
        ]);
    }

    /**
     * @param  array<string, mixed>  $lastTouch
     * @param  array<string, mixed>  $firstTouch
     * @param  array<string, mixed>  $sessionData
     */
    protected function pick(Request $request, string $key, array $lastTouch, array $firstTouch, array $sessionData): ?string
    {
        $requestValue = $request->input($key);

        if (is_string($requestValue) && trim($requestValue) !== '') {
            return trim($requestValue);
        }

        $lastTouchValue = $lastTouch[$key] ?? null;
        if (is_string($lastTouchValue) && trim($lastTouchValue) !== '') {
            return trim($lastTouchValue);
        }

        $firstTouchValue = $firstTouch[$key] ?? null;
        if (is_string($firstTouchValue) && trim($firstTouchValue) !== '') {
            return trim($firstTouchValue);
        }

        $sessionValue = $sessionData[$key] ?? null;

        if (is_string($sessionValue) && trim($sessionValue) !== '') {
            return trim($sessionValue);
        }

        return null;
    }
}
