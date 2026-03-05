<?php

namespace App\Filament\Resources\ServiceItemTranslationResource\Pages;

use App\Filament\Resources\ServiceItemTranslationResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListServiceItemTranslations extends ListRecords
{
    protected static string $resource = ServiceItemTranslationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
