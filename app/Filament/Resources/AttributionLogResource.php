<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AttributionLogResource\Pages;
use App\Models\AttributionLog;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Tables;
use Filament\Tables\Table;

class AttributionLogResource extends BaseResource
{
    protected static ?string $model = AttributionLog::class;

    protected static ?string $navigationIcon = 'heroicon-o-funnel';

    protected static ?string $navigationGroup = 'Analiz ve Reklam';

    public static function form(Form $form): Form
    {
        return $form->schema([]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('created_at')->dateTime()->sortable(),
                Tables\Columns\TextColumn::make('lead_id')->label('Lead')->sortable(),
                Tables\Columns\TextColumn::make('utm_source')->searchable(),
                Tables\Columns\TextColumn::make('utm_medium')->searchable(),
                Tables\Columns\TextColumn::make('utm_campaign')->searchable(),
                Tables\Columns\TextColumn::make('landing_path')->toggleable(),
                Tables\Columns\TextColumn::make('gclid')->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('fbclid')->toggleable(isToggledHiddenByDefault: true),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAttributionLogs::route('/'),
            'view' => Pages\ViewAttributionLog::route('/{record}'),
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
