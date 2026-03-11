<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductCategoryTranslationResource\Pages;
use App\Models\ProductCategoryTranslation;
use App\Support\AdminLanguageOptions;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Tables;
use Filament\Tables\Table;

class ProductCategoryTranslationResource extends BaseResource
{
    protected static ?string $model = ProductCategoryTranslation::class;

    protected static ?string $navigationIcon = 'heroicon-o-language';

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('product_category_id')
                    ->relationship('productCategory', 'id')
                    ->required(),
                Forms\Components\Select::make('lang')
                    ->options(AdminLanguageOptions::options())
                    ->required(),
                Forms\Components\TextInput::make('name')
                    ->required(),
                Forms\Components\TextInput::make('slug')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('product_category_id')
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
            'index' => Pages\ListProductCategoryTranslations::route('/'),
            'create' => Pages\CreateProductCategoryTranslation::route('/create'),
            'edit' => Pages\EditProductCategoryTranslation::route('/{record}/edit'),
        ];
    }
}
