<?php

namespace App\Filament\Resources\CaseStudyResource\Pages;

use App\Filament\Support\EnforcesTranslationCoverage;
use App\Filament\Resources\CaseStudyResource;
use Filament\Resources\Pages\CreateRecord;

class CreateCaseStudy extends CreateRecord
{
    use EnforcesTranslationCoverage;

    protected static string $resource = CaseStudyResource::class;

    /**
     * @param array<string, mixed> $data
     * @return array<string, mixed>
     */
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        return $this->enforceCoverageOnCreate($data, 'is_active', 'case study');
    }
}
