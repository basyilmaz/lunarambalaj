<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class UserResource extends BaseResource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $navigationGroup = 'Sistem';

    protected static ?string $modelLabel = 'Kullanici';

    protected static ?string $pluralModelLabel = 'Kullanicilar';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true),
                Forms\Components\Select::make('role')
                    ->options([
                        User::ROLE_ADMIN => 'Admin',
                        User::ROLE_EDITOR => 'Editor',
                        User::ROLE_VIEWER => 'Viewer',
                        User::ROLE_DEVELOPER => 'Developer',
                        User::ROLE_MARKETING_MANAGER => 'Marketing Manager',
                    ])
                    ->required(),
                Forms\Components\TextInput::make('password')
                    ->password()
                    ->revealable()
                    ->dehydrated(fn (?string $state): bool => filled($state))
                    ->dehydrateStateUsing(fn (string $state): string => Hash::make($state))
                    ->required(fn (?Model $record): bool => $record === null)
                    ->minLength(8),
                Forms\Components\DateTimePicker::make('email_verified_at'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('role')
                    ->badge(),
                Tables\Columns\IconColumn::make('email_verified_at')
                    ->label('Dogrulandi')
                    ->boolean(fn (User $record): bool => $record->email_verified_at !== null),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('role')
                    ->options([
                        User::ROLE_ADMIN => 'Admin',
                        User::ROLE_EDITOR => 'Editor',
                        User::ROLE_VIEWER => 'Viewer',
                        User::ROLE_DEVELOPER => 'Developer',
                        User::ROLE_MARKETING_MANAGER => 'Marketing Manager',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }

    public static function canViewAny(): bool
    {
        return auth()->check() && auth()->user()?->isAdmin();
    }

    public static function canView($record): bool
    {
        return auth()->check() && auth()->user()?->isAdmin();
    }

    public static function canCreate(): bool
    {
        return auth()->check() && auth()->user()?->isAdmin();
    }

    public static function canEdit($record): bool
    {
        return auth()->check() && auth()->user()?->isAdmin();
    }

    public static function canDelete($record): bool
    {
        return auth()->check() && auth()->user()?->isAdmin();
    }
}
