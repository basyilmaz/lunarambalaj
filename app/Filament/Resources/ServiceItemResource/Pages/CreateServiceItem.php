<?php

namespace App\Filament\Resources\ServiceItemResource\Pages;

use App\Filament\Support\EnforcesTranslationCoverage;
use App\Filament\Resources\ServiceItemResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateServiceItem extends CreateRecord
{
    use EnforcesTranslationCoverage;

    protected static string $resource = ServiceItemResource::class;

    /**
     * @param array<string, mixed> $data
     * @return array<string, mixed>
     */
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        return $this->enforceCoverageOnCreate($data, 'is_active', 'service');
    }
}
