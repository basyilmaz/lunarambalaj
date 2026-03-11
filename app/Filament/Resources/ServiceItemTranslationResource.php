<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ServiceItemTranslationResource\Pages;
use App\Models\ServiceItemTranslation;
use App\Support\AdminLanguageOptions;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Tables;
use Filament\Tables\Table;

class ServiceItemTranslationResource extends BaseResource
{
    protected static ?string $model = ServiceItemTranslation::class;

    protected static ?string $navigationIcon = 'heroicon-o-language';

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('service_item_id')
                    ->relationship('serviceItem', 'id')
                    ->required(),
                Forms\Components\Select::make('lang')
                    ->options(AdminLanguageOptions::options())
                    ->required(),
                Forms\Components\TextInput::make('title')
                    ->required(),
                Forms\Components\RichEditor::make('body')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('service_item_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('lang')
                    ->badge(),
                Tables\Columns\TextColumn::make('title')
                    ->searchable(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListServiceItemTranslations::route('/'),
            'create' => Pages\CreateServiceItemTranslation::route('/create'),
            'edit' => Pages\EditServiceItemTranslation::route('/{record}/edit'),
        ];
    }
}
