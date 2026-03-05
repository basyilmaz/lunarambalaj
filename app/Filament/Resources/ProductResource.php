<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Filament\Resources\ProductResource\RelationManagers;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProductResource extends BaseResource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-cube';

    protected static ?string $navigationGroup = 'Urunler';

    protected static ?string $modelLabel = 'Urun';

    protected static ?string $pluralModelLabel = 'Urunler';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('category_id')
                    ->label('Kategori')
                    ->relationship(
                        name: 'category',
                        titleAttribute: 'id',
                        modifyQueryUsing: fn (Builder $query) => $query->with('translations'),
                    )
                    ->getOptionLabelFromRecordUsing(fn ($record): string => $record->translation(app()->getLocale())?->name
                        ?? $record->translation('tr')?->name
                        ?? ('#' . $record->id))
                    ->required(),
                Forms\Components\TextInput::make('min_order')
                    ->label('Min. Siparis')
                    ->required()
                    ->numeric()
                    ->default(5000),
                Forms\Components\Toggle::make('has_print')
                    ->label('Baski var')
                    ->required(),
                Forms\Components\Toggle::make('has_wrapping')
                    ->label('Jelatin var')
                    ->required(),
                Forms\Components\Toggle::make('is_active')
                    ->label('Aktif')
                    ->required(),
                Forms\Components\FileUpload::make('image')
                    ->label('Kapak gorseli')
                    ->image(),
                Forms\Components\FileUpload::make('images')
                    ->label('Galeri gorselleri')
                    ->multiple()
                    ->reorderable()
                    ->image(),
                Forms\Components\KeyValue::make('specs')
                    ->label('Teknik ozellikler')
                    ->keyLabel('Ozellik')
                    ->valueLabel('Deger')
                    ->reorderable(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('category_id')
                    ->label('Kategori')
                    ->formatStateUsing(fn (Product $record): string => $record->category?->translation(app()->getLocale())?->name
                        ?? $record->category?->translation('tr')?->name
                        ?? '-')
                    ->sortable(),
                Tables\Columns\TextColumn::make('min_order')
                    ->label('Min. Siparis')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\IconColumn::make('has_print')
                    ->label('Baski')
                    ->boolean(),
                Tables\Columns\IconColumn::make('has_wrapping')
                    ->label('Jelatin')
                    ->boolean(),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean(),
                Tables\Columns\ImageColumn::make('image')
                    ->label('Kapak'),
                Tables\Columns\TextColumn::make('images')
                    ->label('Galeri')
                    ->formatStateUsing(fn ($state): int => is_array($state) ? count($state) : 0),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Aktif'),
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
                Tables\Actions\ForceDeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\TranslationsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([SoftDeletingScope::class]);
    }
}
