<?php

namespace App\Filament\Resources\ProductCategoryTranslationResource\Pages;

use App\Filament\Resources\ProductCategoryTranslationResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditProductCategoryTranslation extends EditRecord
{
    protected static string $resource = ProductCategoryTranslationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
