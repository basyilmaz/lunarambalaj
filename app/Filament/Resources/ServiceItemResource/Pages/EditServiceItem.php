<?php

namespace App\Filament\Resources\ServiceItemResource\Pages;

use App\Filament\Support\EnforcesTranslationCoverage;
use App\Filament\Resources\ServiceItemResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditServiceItem extends EditRecord
{
    use EnforcesTranslationCoverage;

    protected static string $resource = ServiceItemResource::class;

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
        return $this->enforceCoverageOnSave($this->record, $data, 'is_active', 'service');
    }
}
