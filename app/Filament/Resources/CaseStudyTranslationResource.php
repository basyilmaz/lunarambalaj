<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CaseStudyTranslationResource\Pages;
use App\Models\CaseStudyTranslation;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class CaseStudyTranslationResource extends BaseResource
{
    protected static ?string $model = CaseStudyTranslation::class;

    protected static ?string $navigationIcon = 'heroicon-o-language';

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('case_study_id')
                    ->relationship('caseStudy', 'client_name')
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
                    ->required()
                    ->maxLength(255)
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn ($state, Forms\Set $set) => $set('slug', Str::slug((string) $state))),
                Forms\Components\TextInput::make('slug')
                    ->required()
                    ->maxLength(255),
                Forms\Components\RichEditor::make('challenge')
                    ->columnSpanFull(),
                Forms\Components\RichEditor::make('solution')
                    ->columnSpanFull(),
                Forms\Components\RichEditor::make('results')
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
                Tables\Columns\TextColumn::make('caseStudy.client_name')
                    ->label('Musteri')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('lang')
                    ->badge()
                    ->searchable(),
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
            'index' => Pages\ListCaseStudyTranslations::route('/'),
            'create' => Pages\CreateCaseStudyTranslation::route('/create'),
            'edit' => Pages\EditCaseStudyTranslation::route('/{record}/edit'),
        ];
    }
}
