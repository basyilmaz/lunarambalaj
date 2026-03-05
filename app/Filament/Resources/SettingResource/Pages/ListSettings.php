<?php

namespace App\Filament\Resources\SettingResource\Pages;

use App\Filament\Resources\SettingResource;
use App\Models\Setting;
use Filament\Resources\Pages\ListRecords;

class ListSettings extends ListRecords
{
    protected static string $resource = SettingResource::class;

    public function mount(): void
    {
        parent::mount();

        $setting = Setting::query()->firstOrCreate(['id' => 1]);
        $this->redirect(SettingResource::getUrl('edit', ['record' => $setting]));
    }

    protected function getHeaderActions(): array
    {
        return [];
    }
}
