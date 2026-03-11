<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FaqTranslationResource\Pages;
use App\Models\FaqTranslation;
use App\Support\AdminLanguageOptions;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Tables;
use Filament\Tables\Table;

class FaqTranslationResource extends BaseResource
{
    protected static ?string $model = FaqTranslation::class;

    protected static ?string $navigationIcon = 'heroicon-o-language';

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('faq_id')
                    ->relationship('faq', 'id')
                    ->required(),
                Forms\Components\Select::make('lang')
                    ->options(AdminLanguageOptions::options())
                    ->required(),
                Forms\Components\TextInput::make('question')
                    ->required(),
                Forms\Components\RichEditor::make('answer')
                    ->required()
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('faq_id')->numeric(),
                Tables\Columns\TextColumn::make('lang')->badge(),
                Tables\Columns\TextColumn::make('question')->searchable(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListFaqTranslations::route('/'),
            'create' => Pages\CreateFaqTranslation::route('/create'),
            'edit' => Pages\EditFaqTranslation::route('/{record}/edit'),
        ];
    }
}
