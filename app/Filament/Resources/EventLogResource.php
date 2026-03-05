<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EventLogResource\Pages;
use App\Models\EventLog;
use Filament\Forms\Form;
use Filament\Tables;
use Filament\Tables\Table;

class EventLogResource extends BaseResource
{
    protected static ?string $model = EventLog::class;

    protected static ?string $navigationIcon = 'heroicon-o-signal';

    protected static ?string $navigationGroup = 'Analiz ve Reklam';

    public static function form(Form $form): Form
    {
        return $form->schema([]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('created_at')->dateTime()->sortable(),
                Tables\Columns\TextColumn::make('event_key')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('locale')->toggleable(),
                Tables\Columns\TextColumn::make('page_path')->limit(40)->toggleable(),
                Tables\Columns\TextColumn::make('lead_id')->label('Lead')->sortable()->toggleable(),
                Tables\Columns\TextColumn::make('session_id')->limit(16)->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEventLogs::route('/'),
            'view' => Pages\ViewEventLog::route('/{record}'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canEdit($record): bool
    {
        return false;
    }

    public static function canDelete($record): bool
    {
        return false;
    }
}

