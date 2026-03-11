<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Filament\Support\EnforcesTranslationCoverage;
use App\Filament\Resources\ProductResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateProduct extends CreateRecord
{
    use EnforcesTranslationCoverage;

    protected static string $resource = ProductResource::class;

    /**
     * @param array<string, mixed> $data
     * @return array<string, mixed>
     */
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        return $this->enforceCoverageOnCreate($data, 'is_active', 'product');
    }
}
