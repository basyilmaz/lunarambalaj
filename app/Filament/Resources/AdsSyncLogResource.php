<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AdsSyncLogResource\Pages;
use App\Models\AdsSyncLog;
use Filament\Forms\Form;
use Filament\Tables;
use Filament\Tables\Table;

class AdsSyncLogResource extends BaseResource
{
    protected static ?string $model = AdsSyncLog::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

    protected static ?string $navigationGroup = 'Analiz ve Reklam';

    protected static ?string $modelLabel = 'Ads Sync Log';

    protected static ?string $pluralModelLabel = 'Ads Sync Logs';

    protected static ?int $navigationSort = 7;

    public static function form(Form $form): Form
    {
        return $form->schema([]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tarih')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('platform')
                    ->badge()
                    ->searchable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'success' => 'success',
                        'failed' => 'danger',
                        'skipped' => 'warning',
                        default => 'gray',
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('from_date')
                    ->label('From')
                    ->date(),
                Tables\Columns\TextColumn::make('to_date')
                    ->label('To')
                    ->date(),
                Tables\Columns\TextColumn::make('fetched')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('upserted')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('error_message')
                    ->label('Hata')
                    ->limit(80)
                    ->toggleable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'success' => 'Success',
                        'failed' => 'Failed',
                        'skipped' => 'Skipped',
                    ]),
                Tables\Filters\SelectFilter::make('platform')
                    ->options([
                        'google_ads' => 'Google Ads',
                        'meta_ads' => 'Meta Ads',
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAdsSyncLogs::route('/'),
            'view' => Pages\ViewAdsSyncLog::route('/{record}'),
        ];
    }

    public static function canViewAny(): bool
    {
        $user = auth()->user();

        return $user !== null
            && ($user->isAdmin() || $user->isDeveloper() || $user->isMarketingManager());
    }

    public static function canView($record): bool
    {
        return static::canViewAny();
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

