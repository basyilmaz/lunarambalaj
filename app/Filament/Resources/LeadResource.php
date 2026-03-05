<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LeadResource\Pages;
use App\Models\Lead;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class LeadResource extends BaseResource
{
    protected static ?string $model = Lead::class;

    protected static ?string $navigationIcon = 'heroicon-o-inbox';

    protected static ?string $navigationGroup = 'Satis ve Talepler';

    protected static ?string $modelLabel = 'Lead';

    protected static ?string $pluralModelLabel = 'Leadler';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('type')
                    ->options([
                        'quote' => 'Teklif',
                        'contact' => 'Iletisim',
                    ])
                    ->disabled(),
                Forms\Components\Select::make('status')
                    ->options([
                        'new' => 'Yeni',
                        'read' => 'Okundu',
                        'replied' => 'Yanitlandi',
                        'archived' => 'Arsiv',
                    ])
                    ->required(),
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->disabled(),
                Forms\Components\TextInput::make('company')
                    ->disabled(),
                Forms\Components\TextInput::make('phone')
                    ->tel()
                    ->disabled(),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->disabled(),
                Forms\Components\Select::make('assigned_to')
                    ->label('Atanan kullanici')
                    ->relationship('assignee', 'name')
                    ->searchable()
                    ->preload(),
                Forms\Components\Textarea::make('message')
                    ->disabled()
                    ->rows(4)
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('notes')
                    ->label('Admin notu')
                    ->rows(4)
                    ->columnSpanFull(),
                Forms\Components\KeyValue::make('meta')
                    ->disabled()
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('type')
                    ->label('Tip')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => $state === 'quote' ? 'Teklif' : 'Iletisim')
                    ->searchable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('Durum')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'new' => 'warning',
                        'read' => 'info',
                        'replied' => 'success',
                        'archived' => 'gray',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('name')
                    ->label('Ad')
                    ->searchable(),
                Tables\Columns\TextColumn::make('company')
                    ->label('Sirket')
                    ->searchable(),
                Tables\Columns\TextColumn::make('phone')
                    ->label('Telefon')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->label('E-posta')
                    ->searchable(),
                Tables\Columns\TextColumn::make('assignee.name')
                    ->label('Atanan'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Olusturma')
                    ->dateTime()
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'new' => 'Yeni',
                        'read' => 'Okundu',
                        'replied' => 'Yanitlandi',
                        'archived' => 'Arsiv',
                    ]),
                Tables\Filters\SelectFilter::make('type')
                    ->options([
                        'quote' => 'Teklif',
                        'contact' => 'Iletisim',
                    ]),
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
                Tables\Actions\ForceDeleteAction::make(),
                Tables\Actions\Action::make('mark_replied')
                    ->label('Yanitlandi')
                    ->color('success')
                    ->action(fn (Lead $record) => $record->update(['status' => 'replied'])),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('bulk_status')
                        ->label('Durum guncelle')
                        ->form([
                            Forms\Components\Select::make('status')
                                ->options([
                                    'new' => 'Yeni',
                                    'read' => 'Okundu',
                                    'replied' => 'Yanitlandi',
                                    'archived' => 'Arsiv',
                                ])
                                ->required(),
                        ])
                        ->action(function ($records, array $data): void {
                            $records->each(fn (Lead $lead) => $lead->update(['status' => $data['status']]));
                        }),
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLeads::route('/'),
            'view' => Pages\ViewLead::route('/{record}'),
            'edit' => Pages\EditLead::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([SoftDeletingScope::class]);
    }

    public static function canCreate(): bool
    {
        return false;
    }
}
