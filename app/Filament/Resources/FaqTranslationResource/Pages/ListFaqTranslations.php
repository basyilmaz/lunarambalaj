<?php

namespace App\Filament\Resources\FaqTranslationResource\Pages;

use App\Filament\Resources\FaqTranslationResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListFaqTranslations extends ListRecords
{
    protected static string $resource = FaqTranslationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
