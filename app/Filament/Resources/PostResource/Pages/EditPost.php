<?php

namespace App\Filament\Resources\PostResource\Pages;

use App\Filament\Support\EnforcesTranslationCoverage;
use App\Filament\Resources\PostResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPost extends EditRecord
{
    use EnforcesTranslationCoverage;

    protected static string $resource = PostResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    /**
     * @param array<string, mixed> $data
     * @return array<string, mixed>
     */
    protected function mutateFormDataBeforeSave(array $data): array
    {
        return $this->enforceCoverageOnSave($this->record, $data, 'is_active', 'post');
    }
}
