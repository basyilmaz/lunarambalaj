<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SettingResource\Pages;
use App\Models\Setting;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Tables;
use Filament\Tables\Table;

class SettingResource extends BaseResource
{
    protected static ?string $model = Setting::class;

    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static ?string $navigationGroup = 'Sistem';

    protected static ?string $modelLabel = 'Ayar';

    protected static ?string $pluralModelLabel = 'Site ayarlari';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Tabs::make('Ayarlar')
                    ->tabs([
                        Forms\Components\Tabs\Tab::make('Genel')
                            ->schema([
                                Forms\Components\TextInput::make('company_name_tr')
                                    ->label('Sirket adi (TR)'),
                                Forms\Components\TextInput::make('company_name_en')
                                    ->label('Company name (EN)'),
                                Forms\Components\TextInput::make('min_order_default')
                                    ->label('Varsayilan min. siparis')
                                    ->numeric()
                                    ->minValue(1),
                                Forms\Components\TextInput::make('hero_h1_tr')
                                    ->label('Hero baslik (TR)'),
                                Forms\Components\TextInput::make('hero_h1_en')
                                    ->label('Hero heading (EN)'),
                                Forms\Components\Textarea::make('hero_subtitle_tr')
                                    ->label('Hero alt metin (TR)')
                                    ->rows(3),
                                Forms\Components\Textarea::make('hero_subtitle_en')
                                    ->label('Hero subtext (EN)')
                                    ->rows(3),
                                Forms\Components\Textarea::make('footer_short_tr')
                                    ->label('Footer kisa metin (TR)')
                                    ->rows(3),
                                Forms\Components\Textarea::make('footer_short_en')
                                    ->label('Footer short text (EN)')
                                    ->rows(3),
                            ]),
                        Forms\Components\Tabs\Tab::make('Iletisim')
                            ->schema([
                                Forms\Components\TextInput::make('phone')->tel(),
                                Forms\Components\TextInput::make('email')->email(),
                                Forms\Components\TextInput::make('email_secondary')->email(),
                                Forms\Components\Textarea::make('address')->rows(2),
                                Forms\Components\Textarea::make('working_hours')->rows(3),
                                Forms\Components\TextInput::make('whatsapp'),
                            ]),
                        Forms\Components\Tabs\Tab::make('Sosyal')
                            ->schema([
                                Forms\Components\TextInput::make('facebook'),
                                Forms\Components\TextInput::make('instagram'),
                                Forms\Components\TextInput::make('linkedin'),
                            ]),
                        Forms\Components\Tabs\Tab::make('Reklam')
                            ->schema([
                                Forms\Components\TextInput::make('gtm_id')
                                    ->label('GTM ID'),
                                Forms\Components\TextInput::make('meta_pixel_id')
                                    ->label('Meta Pixel ID'),
                            ]),
                    ])
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('company_name_tr')
                    ->label('Sirket')
                    ->searchable(),
                Tables\Columns\TextColumn::make('phone')
                    ->label('Telefon')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->label('E-posta')
                    ->searchable(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Guncelleme')
                    ->dateTime()
                    ->sortable(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSettings::route('/'),
            'edit' => Pages\EditSetting::route('/{record}/edit'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canDelete($record): bool
    {
        return false;
    }

    public static function canDeleteAny(): bool
    {
        return false;
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

    public static function canEdit($record): bool
    {
        return static::canViewAny();
    }
}
