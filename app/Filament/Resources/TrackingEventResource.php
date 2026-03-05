<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TrackingEventResource\Pages;
use App\Models\TrackingEvent;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Tables;
use Filament\Tables\Table;

class TrackingEventResource extends BaseResource
{
    protected static ?string $model = TrackingEvent::class;

    protected static ?string $navigationIcon = 'heroicon-o-bolt';

    protected static ?string $navigationGroup = 'Analiz ve Reklam';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('event_key')->required()->maxLength(100),
            Forms\Components\TextInput::make('display_name')->required()->maxLength(255),
            Forms\Components\KeyValue::make('schema')
                ->keyLabel('Param')
                ->valueLabel('Type/Note')
                ->columnSpanFull(),
            Forms\Components\Toggle::make('is_active')->required(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('event_key')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('display_name')->searchable(),
                Tables\Columns\IconColumn::make('is_active')->boolean(),
                Tables\Columns\TextColumn::make('updated_at')->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
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
            'index' => Pages\ListTrackingEvents::route('/'),
            'create' => Pages\CreateTrackingEvent::route('/create'),
            'edit' => Pages\EditTrackingEvent::route('/{record}/edit'),
        ];
    }
}
