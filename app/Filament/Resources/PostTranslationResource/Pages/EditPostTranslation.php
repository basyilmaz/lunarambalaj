<?php

namespace App\Filament\Resources\PostTranslationResource\Pages;

use App\Filament\Resources\PostTranslationResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPostTranslation extends EditRecord
{
    protected static string $resource = PostTranslationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
