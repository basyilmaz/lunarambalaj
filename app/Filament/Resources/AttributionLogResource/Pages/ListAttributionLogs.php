<?php

namespace App\Filament\Resources\AttributionLogResource\Pages;

use App\Filament\Resources\AttributionLogResource;
use Filament\Resources\Pages\ListRecords;

class ListAttributionLogs extends ListRecords
{
    protected static string $resource = AttributionLogResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
