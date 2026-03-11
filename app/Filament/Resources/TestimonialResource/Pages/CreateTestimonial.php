<?php

namespace App\Filament\Resources\TestimonialResource\Pages;

use App\Filament\Support\EnforcesTranslationCoverage;
use App\Filament\Resources\TestimonialResource;
use Filament\Resources\Pages\CreateRecord;

class CreateTestimonial extends CreateRecord
{
    use EnforcesTranslationCoverage;

    protected static string $resource = TestimonialResource::class;

    /**
     * @param array<string, mixed> $data
     * @return array<string, mixed>
     */
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        return $this->enforceCoverageOnCreate($data, 'is_active', 'testimonial');
    }
}
