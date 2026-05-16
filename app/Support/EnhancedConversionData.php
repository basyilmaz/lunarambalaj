<?php

namespace App\Support;

use Illuminate\Support\Str;

class EnhancedConversionData
{
    public static function normalizeEmail(?string $email): ?string
    {
        if (! is_string($email)) {
            return null;
        }

        $trimmed = trim(Str::lower($email));

        return $trimmed === '' ? null : $trimmed;
    }

    /**
     * Normalize a phone number to E.164 (e.g. +905551234567).
     * Defaults the country code to +90 (Turkey) for 10- or 11-digit inputs.
     */
    public static function normalizePhone(?string $phone, string $defaultCountryCode = '90'): ?string
    {
        if (! is_string($phone)) {
            return null;
        }

        $hasPlus = str_starts_with(trim($phone), '+');
        $digits = preg_replace('/\D+/', '', $phone) ?? '';

        if ($digits === '') {
            return null;
        }

        if ($hasPlus) {
            return '+' . $digits;
        }

        if (str_starts_with($digits, '00')) {
            return '+' . substr($digits, 2);
        }

        if (str_starts_with($digits, '0') && strlen($digits) === 11) {
            return '+' . $defaultCountryCode . substr($digits, 1);
        }

        if (strlen($digits) === 10) {
            return '+' . $defaultCountryCode . $digits;
        }

        return '+' . $digits;
    }
}
