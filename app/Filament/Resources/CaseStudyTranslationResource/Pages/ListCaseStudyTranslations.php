<?php

namespace App\Filament\Resources\CaseStudyTranslationResource\Pages;

use App\Filament\Resources\CaseStudyTranslationResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCaseStudyTranslations extends ListRecords
{
    protected static string $resource = CaseStudyTranslationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
