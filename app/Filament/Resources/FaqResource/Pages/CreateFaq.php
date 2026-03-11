<?php

namespace App\Filament\Resources\FaqResource\Pages;

use App\Filament\Support\EnforcesTranslationCoverage;
use App\Filament\Resources\FaqResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateFaq extends CreateRecord
{
    use EnforcesTranslationCoverage;

    protected static string $resource = FaqResource::class;

    /**
     * @param array<string, mixed> $data
     * @return array<string, mixed>
     */
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        return $this->enforceCoverageOnCreate($data, 'is_active', 'faq');
    }
}
