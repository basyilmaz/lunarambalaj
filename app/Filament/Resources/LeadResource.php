<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LeadResource\Pages;
use App\Models\Lead;
use App\Support\LeadConversion;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
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
                        'qualified' => 'Nitelikli',
                        'won' => 'Kazanildi',
                        'lost' => 'Kaybedildi',
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
                Forms\Components\TextInput::make('estimated_value')
                    ->label('Tahmini deger (TRY)')
                    ->numeric()
                    ->step(0.01)
                    ->minValue(0)
                    ->helperText('Bu lead anlasmaya donerse beklenen TRY tutari. ads:upload-click-conversions komutu won_deal sinyalini bu deger ile Google Ads e gonderir.'),
                Forms\Components\TextInput::make('gclid')
                    ->label('GCLID')
                    ->disabled()
                    ->helperText('Google Ads tiklamasinin kimligi. won_deal upload icin gerekli.'),
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
                        'replied' => 'primary',
                        'qualified' => 'info',
                        'won' => 'success',
                        'lost' => 'danger',
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
                        'qualified' => 'Nitelikli',
                        'won' => 'Kazanildi',
                        'lost' => 'Kaybedildi',
                        'archived' => 'Arsiv',
                    ])
                    ->multiple(),
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
                    ->color('primary')
                    ->action(fn (Lead $record) => $record->update(['status' => 'replied'])),
                Tables\Actions\Action::make('mark_qualified')
                    ->label('Nitelikli')
                    ->icon('heroicon-o-check-badge')
                    ->color('info')
                    ->requiresConfirmation()
                    ->action(function (Lead $record): void {
                        $record->update(['status' => 'qualified']);
                        LeadConversion::recordEvent($record, 'qualified_lead');
                        Notification::make()
                            ->title('Lead nitelikli olarak isaretlendi')
                            ->body('qualified_lead etkinligi kaydedildi; gece bu lead in gclid si Google Ads e gonderilecek.')
                            ->success()
                            ->send();
                    }),
                Tables\Actions\Action::make('mark_won')
                    ->label('Kazanildi')
                    ->icon('heroicon-o-trophy')
                    ->color('success')
                    ->form([
                        Forms\Components\TextInput::make('estimated_value')
                            ->label('Anlasma tutari (TRY)')
                            ->numeric()
                            ->step(0.01)
                            ->minValue(0)
                            ->required()
                            ->default(fn (Lead $record): ?string => $record->estimated_value ? (string) $record->estimated_value : null)
                            ->helperText('Bu tutar won_deal etkinligi ile Google Ads e gonderilir.'),
                    ])
                    ->action(function (Lead $record, array $data): void {
                        $record->update([
                            'status' => 'won',
                            'estimated_value' => $data['estimated_value'],
                        ]);
                        LeadConversion::recordEvent($record, 'won_deal');
                        Notification::make()
                            ->title('Lead kazanildi olarak isaretlendi')
                            ->body('won_deal etkinligi kaydedildi; gece bu lead in gclid si Google Ads e gonderilecek (deger ' . $data['estimated_value'] . ' TRY).')
                            ->success()
                            ->send();
                    }),
                Tables\Actions\Action::make('mark_lost')
                    ->label('Kaybedildi')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->action(fn (Lead $record) => $record->update(['status' => 'lost'])),
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
