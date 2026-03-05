<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AdIntegrationResource\Pages;
use App\Models\AdIntegration;
use App\Services\Ads\AdsSyncService;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\CarbonImmutable;

class AdIntegrationResource extends BaseResource
{
    protected static ?string $model = AdIntegration::class;

    protected static ?string $navigationIcon = 'heroicon-o-link';

    protected static ?string $navigationGroup = 'Analiz ve Reklam';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('platform')
                ->options([
                    'google_ads' => 'Google Ads',
                    'meta_ads' => 'Meta Ads',
                    'ga4' => 'Google Analytics 4',
                    'gtm' => 'Google Tag Manager',
                ])
                ->required()
                ->native(false)
                ->disabled(fn (?AdIntegration $record): bool => $record !== null),
            Forms\Components\TextInput::make('name')->maxLength(255),
            Forms\Components\Section::make('Google Ads Credentials')
                ->schema([
                    Forms\Components\TextInput::make('credentials.api_token')
                        ->password()
                        ->required(fn (Forms\Get $get): bool => $get('platform') === 'google_ads' && (bool) $get('is_active')),
                    Forms\Components\TextInput::make('credentials.customer_id')
                        ->required(fn (Forms\Get $get): bool => $get('platform') === 'google_ads' && (bool) $get('is_active')),
                    Forms\Components\TextInput::make('credentials.developer_token')
                        ->password()
                        ->required(fn (Forms\Get $get): bool => $get('platform') === 'google_ads' && (bool) $get('is_active')),
                ])
                ->visible(fn (Forms\Get $get): bool => $get('platform') === 'google_ads'),
            Forms\Components\Section::make('Meta Ads Credentials')
                ->schema([
                    Forms\Components\TextInput::make('credentials.access_token')
                        ->password()
                        ->required(fn (Forms\Get $get): bool => $get('platform') === 'meta_ads' && (bool) $get('is_active')),
                    Forms\Components\TextInput::make('credentials.ad_account_id')
                        ->required(fn (Forms\Get $get): bool => $get('platform') === 'meta_ads' && (bool) $get('is_active')),
                ])
                ->visible(fn (Forms\Get $get): bool => $get('platform') === 'meta_ads'),
            Forms\Components\Section::make('Custom Credentials')
                ->schema([
                    Forms\Components\KeyValue::make('credentials')
                        ->keyLabel('Key')
                        ->valueLabel('Value')
                        ->columnSpanFull(),
                ])
                ->visible(fn (Forms\Get $get): bool => in_array($get('platform'), ['ga4', 'gtm'], true)),
            Forms\Components\Toggle::make('is_active')->required(),
            Forms\Components\DateTimePicker::make('last_sync_at'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('platform')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('name')->searchable(),
                Tables\Columns\IconColumn::make('is_active')->boolean(),
                Tables\Columns\TextColumn::make('last_sync_at')->dateTime()->sortable(),
                Tables\Columns\TextColumn::make('last_sync_status')
                    ->badge()
                    ->color(fn (?string $state): string => match ($state) {
                        'success' => 'success',
                        'failed' => 'danger',
                        'skipped' => 'warning',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('last_sync_error')
                    ->limit(60)
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('syncNow')
                    ->label('Sync Now')
                    ->icon('heroicon-o-arrow-path')
                    ->visible(fn (AdIntegration $record): bool => in_array($record->platform, ['google_ads', 'meta_ads'], true))
                    ->action(function (AdIntegration $record): void {
                        $service = app(AdsSyncService::class);
                        $from = CarbonImmutable::now()->subDays(7)->startOfDay();
                        $to = CarbonImmutable::now()->endOfDay();
                        $result = $service->syncPlatform($record->platform, $from, $to);

                        Notification::make()
                            ->title('Sync completed')
                            ->body(sprintf(
                                '%s | fetched: %d, upserted: %d',
                                $result['platform'],
                                $result['fetched'],
                                $result['upserted']
                            ))
                            ->success()
                            ->send();
                    }),
                Tables\Actions\Action::make('testConnection')
                    ->label('Test Connection')
                    ->icon('heroicon-o-wifi')
                    ->color('gray')
                    ->visible(fn (AdIntegration $record): bool => in_array($record->platform, ['google_ads', 'meta_ads'], true))
                    ->action(function (AdIntegration $record): void {
                        $service = app(AdsSyncService::class);
                        $from = CarbonImmutable::now()->subDay()->startOfDay();
                        $to = CarbonImmutable::now()->endOfDay();
                        $result = $service->syncPlatform($record->platform, $from, $to);

                        Notification::make()
                            ->title('Connection test result')
                            ->body(sprintf(
                                '%s | fetched: %d, upserted: %d',
                                $result['platform'],
                                $result['fetched'],
                                $result['upserted']
                            ))
                            ->success()
                            ->send();
                    }),
                Tables\Actions\Action::make('rotateCredentials')
                    ->label('Rotate Credentials')
                    ->icon('heroicon-o-key')
                    ->color('warning')
                    ->visible(function (AdIntegration $record): bool {
                        $user = auth()->user();

                        return in_array($record->platform, ['google_ads', 'meta_ads'], true)
                            && $user !== null
                            && ($user->isAdmin() || $user->isDeveloper());
                    })
                    ->form(function (AdIntegration $record): array {
                        if ($record->platform === 'google_ads') {
                            return [
                                Forms\Components\TextInput::make('api_token')
                                    ->password()
                                    ->required(),
                                Forms\Components\TextInput::make('customer_id')
                                    ->required(),
                                Forms\Components\TextInput::make('developer_token')
                                    ->password()
                                    ->required(),
                            ];
                        }

                        return [
                            Forms\Components\TextInput::make('access_token')
                                ->password()
                                ->required(),
                            Forms\Components\TextInput::make('ad_account_id')
                                ->required(),
                        ];
                    })
                    ->action(function (AdIntegration $record, array $data): void {
                        $credentials = $record->credentials;

                        foreach ($data as $key => $value) {
                            if (is_string($value) && trim($value) !== '') {
                                $credentials[$key] = trim($value);
                            }
                        }

                        $record->forceFill([
                            'credentials' => $credentials,
                            'last_sync_status' => 'skipped',
                            'last_sync_error' => 'Credentials rotated manually',
                        ])->save();

                        Notification::make()
                            ->title('Credentials rotated')
                            ->success()
                            ->send();
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAdIntegrations::route('/'),
            'create' => Pages\CreateAdIntegration::route('/create'),
            'edit' => Pages\EditAdIntegration::route('/{record}/edit'),
        ];
    }
}
