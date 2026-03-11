<?php

namespace App\Support;

final class AssetVariant
{
    /**
     * Resolve a public asset path and prefer the .webp sibling when it exists.
     */
    public static function optimized(?string $path, ?string $fallback = null): string
    {
        $candidate = trim((string) ($path ?: $fallback), '/');
        if ($candidate === '') {
            return '';
        }

        $dot = strrpos($candidate, '.');
        if ($dot === false) {
            return $candidate;
        }

        $ext = strtolower(substr($candidate, $dot + 1));
        if (! in_array($ext, ['jpg', 'jpeg', 'png'], true)) {
            return $candidate;
        }

        $webp = substr($candidate, 0, $dot) . '.webp';

        static $existsMap = [];
        if (! array_key_exists($webp, $existsMap)) {
            $existsMap[$webp] = file_exists(public_path($webp));
        }

        return $existsMap[$webp] ? $webp : $candidate;
    }
}

