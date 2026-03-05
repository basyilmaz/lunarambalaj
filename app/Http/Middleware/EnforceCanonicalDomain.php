<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnforceCanonicalDomain
{
    public function handle(Request $request, Closure $next): Response
    {
        if (app()->environment('local', 'testing')) {
            return $next($request);
        }

        $canonicalUrl = config('site.canonical_url', config('app.url', 'https://lunarambalaj.com'));
        $canonicalHost = parse_url($canonicalUrl, PHP_URL_HOST) ?: config('site.canonical_host', 'lunarambalaj.com');
        $host = $request->getHost();

        if ($request->getScheme() !== 'https' || $host !== $canonicalHost) {
            $targetHost = str_starts_with($host, 'www.') ? substr($host, 4) : $canonicalHost;
            $target = 'https://' . $targetHost . $request->getRequestUri();

            return redirect()->to($target, 301);
        }

        return $next($request);
    }
}
