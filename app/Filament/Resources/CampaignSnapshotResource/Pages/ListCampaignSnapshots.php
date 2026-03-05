<?php

namespace App\Filament\Resources\CampaignSnapshotResource\Pages;

use App\Filament\Resources\CampaignSnapshotResource;
use Filament\Resources\Pages\ListRecords;

class ListCampaignSnapshots extends ListRecords
{
    protected static string $resource = CampaignSnapshotResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
