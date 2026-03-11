<?php

namespace App\Filament\Resources\ProductCategoryResource\Pages;

use App\Filament\Support\EnforcesTranslationCoverage;
use App\Filament\Resources\ProductCategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateProductCategory extends CreateRecord
{
    use EnforcesTranslationCoverage;

    protected static string $resource = ProductCategoryResource::class;

    /**
     * @param array<string, mixed> $data
     * @return array<string, mixed>
     */
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        return $this->enforceCoverageOnCreate($data, 'is_active', 'category');
    }
}
