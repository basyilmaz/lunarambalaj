<?php

namespace App\Filament\Resources\PageResource\Pages;

use App\Filament\Support\EnforcesTranslationCoverage;
use App\Filament\Resources\PageResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPage extends EditRecord
{
    use EnforcesTranslationCoverage;

    protected static string $resource = PageResource::class;

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
        return $this->enforceCoverageOnSave($this->record, $data, 'is_published', 'page');
    }
}
