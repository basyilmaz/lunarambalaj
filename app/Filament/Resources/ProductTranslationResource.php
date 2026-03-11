<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductTranslationResource\Pages;
use App\Models\ProductTranslation;
use App\Support\AdminLanguageOptions;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Tables;
use Filament\Tables\Table;

class ProductTranslationResource extends BaseResource
{
    protected static ?string $model = ProductTranslation::class;

    protected static ?string $navigationIcon = 'heroicon-o-language';

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('product_id')
                    ->relationship('product', 'id')
                    ->required(),
                Forms\Components\Select::make('lang')
                    ->options(AdminLanguageOptions::options())
                    ->required(),
                Forms\Components\TextInput::make('name')
                    ->required(),
                Forms\Components\TextInput::make('slug')
                    ->required(),
                Forms\Components\Textarea::make('short_desc')
                    ->columnSpanFull(),
                Forms\Components\RichEditor::make('description')
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
                Tables\Columns\TextColumn::make('product_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('lang')
                    ->badge(),
                Tables\Columns\TextColumn::make('name')
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
            'index' => Pages\ListProductTranslations::route('/'),
            'create' => Pages\CreateProductTranslation::route('/create'),
            'edit' => Pages\EditProductTranslation::route('/{record}/edit'),
        ];
    }
}
