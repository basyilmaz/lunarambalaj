<?php

namespace App\Filament\Resources\PageResource\Pages;

use App\Filament\Support\EnforcesTranslationCoverage;
use App\Filament\Resources\PageResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreatePage extends CreateRecord
{
    use EnforcesTranslationCoverage;

    protected static string $resource = PageResource::class;

    /**
     * @param array<string, mixed> $data
     * @return array<string, mixed>
     */
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        return $this->enforceCoverageOnCreate($data, 'is_published', 'page');
    }
}
