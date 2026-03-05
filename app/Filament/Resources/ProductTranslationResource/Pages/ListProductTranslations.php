<?php

namespace App\Filament\Resources\ProductTranslationResource\Pages;

use App\Filament\Resources\ProductTranslationResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListProductTranslations extends ListRecords
{
    protected static string $resource = ProductTranslationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
