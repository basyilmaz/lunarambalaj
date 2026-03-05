<?php

namespace App\Filament\Resources\FaqTranslationResource\Pages;

use App\Filament\Resources\FaqTranslationResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditFaqTranslation extends EditRecord
{
    protected static string $resource = FaqTranslationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
