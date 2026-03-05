<?php

namespace App\Filament\Resources\PostTranslationResource\Pages;

use App\Filament\Resources\PostTranslationResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPostTranslations extends ListRecords
{
    protected static string $resource = PostTranslationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
