<?php

namespace App\Filament\Resources\ProductCategoryTranslationResource\Pages;

use App\Filament\Resources\ProductCategoryTranslationResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListProductCategoryTranslations extends ListRecords
{
    protected static string $resource = ProductCategoryTranslationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
