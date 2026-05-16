<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Response;

class CaptureAttribution
{
    /**
     * Cookie names mirroring the click identifiers we persist across sessions.
     */
    public const COOKIE_KEYS = ['gclid', 'fbclid'];

    public const COOKIE_NAME_PREFIX = '_la_';

    public const COOKIE_TTL_MINUTES = 90 * 24 * 60;

    /**
     * Persist attribution params in session for later lead attribution.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $sessionData = $request->session()->get('attribution', []);
        $keys = ['utm_source', 'utm_medium', 'utm_campaign', 'utm_term', 'utm_content', 'gclid', 'fbclid'];
        $captured = [];

        foreach ($keys as $key) {
            $value = $request->query($key);
            if (is_string($value) && trim($value) !== '') {
                $captured[$key] = trim($value);
            }
        }

        if ($captured !== []) {
            $nowIso = now()->toIso8601String();
            $firstTouch = $sessionData['first_touch'] ?? null;

            if (! is_array($firstTouch)) {
                $firstTouch = [
                    'params' => $captured,
                    'at' => $nowIso,
                    'path' => $request->getPathInfo(),
                ];
            }

            $lastTouch = [
                'params' => $captured,
                'at' => $nowIso,
                'path' => $request->getPathInfo(),
            ];

            $sessionData['first_touch'] = $firstTouch;
            $sessionData['last_touch'] = $lastTouch;
            $sessionData['first_touch_at'] = $firstTouch['at'] ?? null;
            $sessionData['last_touch_at'] = $lastTouch['at'] ?? null;
            $sessionData['landing_path'] = $firstTouch['path'] ?? $request->getPathInfo();
            $sessionData['last_touch_path'] = $lastTouch['path'] ?? $request->getPathInfo();

            foreach ($keys as $key) {
                $sessionData[$key] = $captured[$key] ?? ($sessionData[$key] ?? null);
            }

            $request->session()->put('attribution', $sessionData);
        }

        $response = $next($request);

        foreach (self::COOKIE_KEYS as $cookieKey) {
            $value = $captured[$cookieKey] ?? null;
            if ($value !== null) {
                $response->headers->setCookie(Cookie::create(
                    self::COOKIE_NAME_PREFIX . $cookieKey,
                    $value,
                    now()->addMinutes(self::COOKIE_TTL_MINUTES)->getTimestamp(),
                    '/',
                    null,
                    $request->isSecure(),
                    false,
                    false,
                    'lax'
                ));
            }
        }

        return $response;
    }
}
