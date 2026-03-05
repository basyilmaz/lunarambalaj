<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PageTranslationResource\Pages;
use App\Models\PageTranslation;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Tables;
use Filament\Tables\Table;

class PageTranslationResource extends BaseResource
{
    protected static ?string $model = PageTranslation::class;

    protected static ?string $navigationIcon = 'heroicon-o-language';

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('page_id')
                    ->relationship('page', 'id')
                    ->required(),
                Forms\Components\Select::make('lang')
                    ->options([
                        'tr' => 'Turkce',
                        'en' => 'English',
                        'ru' => 'Russian',
                        'ar' => 'Arabic',
                    ])
                    ->required(),
                Forms\Components\TextInput::make('title')
                    ->required(),
                Forms\Components\TextInput::make('slug')
                    ->required(),
                Forms\Components\RichEditor::make('body')
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('seo_title')
                    ->maxLength(60),
                Forms\Components\Textarea::make('seo_desc')
                    ->maxLength(160),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('page_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('lang')
                    ->badge(),
                Tables\Columns\TextColumn::make('title')
                    ->searchable(),
                Tables\Columns\TextColumn::make('slug')
                    ->searchable(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPageTranslations::route('/'),
            'create' => Pages\CreatePageTranslation::route('/create'),
            'edit' => Pages\EditPageTranslation::route('/{record}/edit'),
        ];
    }
}
