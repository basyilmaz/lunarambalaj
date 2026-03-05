<?php

namespace App\Filament\Resources\ProductTranslationResource\Pages;

use App\Filament\Resources\ProductTranslationResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditProductTranslation extends EditRecord
{
    protected static string $resource = ProductTranslationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
