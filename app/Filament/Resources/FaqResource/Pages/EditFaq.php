<?php

namespace App\Filament\Resources\FaqResource\Pages;

use App\Filament\Support\EnforcesTranslationCoverage;
use App\Filament\Resources\FaqResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditFaq extends EditRecord
{
    use EnforcesTranslationCoverage;

    protected static string $resource = FaqResource::class;

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
        return $this->enforceCoverageOnSave($this->record, $data, 'is_active', 'faq');
    }
}
