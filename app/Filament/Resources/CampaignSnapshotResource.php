<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CampaignSnapshotResource\Pages;
use App\Models\CampaignSnapshot;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Tables;
use Filament\Tables\Table;

class CampaignSnapshotResource extends BaseResource
{
    protected static ?string $model = CampaignSnapshot::class;

    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';

    protected static ?string $navigationGroup = 'Analiz ve Reklam';

    public static function form(Form $form): Form
    {
        return $form->schema([]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('snapshot_date', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('snapshot_date')->date()->sortable(),
                Tables\Columns\TextColumn::make('platform')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('campaign_name')->searchable(),
                Tables\Columns\TextColumn::make('spend')->money('TRY')->sortable(),
                Tables\Columns\TextColumn::make('clicks')->numeric()->sortable(),
                Tables\Columns\TextColumn::make('impressions')->numeric()->sortable(),
                Tables\Columns\TextColumn::make('conversions')->numeric()->sortable(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCampaignSnapshots::route('/'),
            'view' => Pages\ViewCampaignSnapshot::route('/{record}'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canEdit($record): bool
    {
        return false;
    }

    public static function canDelete($record): bool
    {
        return false;
    }
}
