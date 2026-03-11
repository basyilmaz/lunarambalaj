<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TestimonialTranslationResource\Pages;
use App\Models\TestimonialTranslation;
use App\Support\AdminLanguageOptions;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Tables;
use Filament\Tables\Table;

class TestimonialTranslationResource extends BaseResource
{
    protected static ?string $model = TestimonialTranslation::class;

    protected static ?string $navigationIcon = 'heroicon-o-language';

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('testimonial_id')
                    ->relationship('testimonial', 'author_name')
                    ->required(),
                Forms\Components\Select::make('lang')
                    ->options(AdminLanguageOptions::options())
                    ->required(),
                Forms\Components\RichEditor::make('content')
                    ->required()
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('testimonial.author_name')
                    ->label('Yazar')
                    ->searchable(),
                Tables\Columns\TextColumn::make('lang')
                    ->badge(),
                Tables\Columns\TextColumn::make('content')
                    ->limit(80),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTestimonialTranslations::route('/'),
            'create' => Pages\CreateTestimonialTranslation::route('/create'),
            'edit' => Pages\EditTestimonialTranslation::route('/{record}/edit'),
        ];
    }
}
