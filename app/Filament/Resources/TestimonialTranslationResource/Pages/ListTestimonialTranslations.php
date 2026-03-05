<?php

namespace App\Filament\Resources\TestimonialTranslationResource\Pages;

use App\Filament\Resources\TestimonialTranslationResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTestimonialTranslations extends ListRecords
{
    protected static string $resource = TestimonialTranslationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
