<?php

namespace App\Filament\Resources\PostResource\Pages;

use App\Filament\Support\EnforcesTranslationCoverage;
use App\Filament\Resources\PostResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreatePost extends CreateRecord
{
    use EnforcesTranslationCoverage;

    protected static string $resource = PostResource::class;

    /**
     * @param array<string, mixed> $data
     * @return array<string, mixed>
     */
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        return $this->enforceCoverageOnCreate($data, 'is_active', 'post');
    }
}
