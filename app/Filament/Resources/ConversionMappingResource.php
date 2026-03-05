<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ConversionMappingResource\Pages;
use App\Models\ConversionMapping;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Tables;
use Filament\Tables\Table;

class ConversionMappingResource extends BaseResource
{
    protected static ?string $model = ConversionMapping::class;

    protected static ?string $navigationIcon = 'heroicon-o-arrow-path-rounded-square';

    protected static ?string $navigationGroup = 'Analiz ve Reklam';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('platform')->required()->maxLength(50),
            Forms\Components\Select::make('tracking_event_id')
                ->relationship('trackingEvent', 'event_key')
                ->searchable()
                ->preload()
                ->required(),
            Forms\Components\TextInput::make('remote_conversion_id')->maxLength(255),
            Forms\Components\TextInput::make('value_rule')->maxLength(255),
            Forms\Components\TextInput::make('dedup_key')->maxLength(255),
            Forms\Components\Toggle::make('is_active')->required(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('platform')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('trackingEvent.event_key')->label('Event')->searchable(),
                Tables\Columns\TextColumn::make('remote_conversion_id')->searchable(),
                Tables\Columns\TextColumn::make('value_rule')->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\IconColumn::make('is_active')->boolean(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListConversionMappings::route('/'),
            'create' => Pages\CreateConversionMapping::route('/create'),
            'edit' => Pages\EditConversionMapping::route('/{record}/edit'),
        ];
    }
}
