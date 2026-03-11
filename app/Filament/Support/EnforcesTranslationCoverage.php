<?php

namespace App\Filament\Support;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\ValidationException;

trait EnforcesTranslationCoverage
{
    /**
     * @param array<string, mixed> $data
     * @return array<string, mixed>
     */
    protected function enforceCoverageOnCreate(array $data, string $statusField, string $recordLabel): array
    {
        if (!($data[$statusField] ?? false)) {
            return $data;
        }

        throw ValidationException::withMessages([
            $statusField => "This {$recordLabel} cannot be activated before all translations are added (" . implode(', ', $this->requiredLocales()) . '). Save inactive first, then add translations.',
        ]);
    }

    /**
     * @param array<string, mixed> $data
     * @return array<string, mixed>
     */
    protected function enforceCoverageOnSave(Model $record, array $data, string $statusField, string $recordLabel): array
    {
        if (!($data[$statusField] ?? false)) {
            return $data;
        }

        $translated = $record->translations()->pluck('lang')->unique()->values()->all();
        $missing = array_values(array_diff($this->requiredLocales(), $translated));

        if (!empty($missing)) {
            throw ValidationException::withMessages([
                $statusField => "This {$recordLabel} is missing translations for: " . implode(', ', $missing) . '. Add them first, then activate.',
            ]);
        }

        return $data;
    }

    /**
     * @return array<int, string>
     */
    private function requiredLocales(): array
    {
        return array_values(array_map(static fn ($locale): string => (string) $locale, config('site.locales', ['tr', 'en'])));
    }
}

