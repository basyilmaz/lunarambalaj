<?php

namespace App\Filament\Resources;

use App\Models\User;
use Filament\Resources\Resource;

abstract class BaseResource extends Resource
{
    /**
     * @var array<int, string>
     */
    protected static array $marketingEditableGroups = [
        'Icerik',
        'Urunler',
        'Satis ve Talepler',
        'Analiz ve Reklam',
    ];

    /**
     * @var array<int, string>
     */
    protected static array $developerEditableGroups = [
        'Analiz ve Reklam',
    ];

    protected static function role(): ?string
    {
        return auth()->user()?->role;
    }

    protected static function currentNavigationGroup(): ?string
    {
        return static::$navigationGroup ?? null;
    }

    protected static function isAdmin(): bool
    {
        return static::role() === User::ROLE_ADMIN;
    }

    protected static function isEditor(): bool
    {
        return static::role() === User::ROLE_EDITOR;
    }

    protected static function isDeveloper(): bool
    {
        return static::role() === User::ROLE_DEVELOPER;
    }

    protected static function isMarketingManager(): bool
    {
        return static::role() === User::ROLE_MARKETING_MANAGER;
    }

    public static function canViewAny(): bool
    {
        return auth()->check();
    }

    public static function canView($record): bool
    {
        return auth()->check();
    }

    public static function canCreate(): bool
    {
        if (static::isAdmin() || static::isEditor()) {
            return true;
        }

        $group = static::currentNavigationGroup();

        if (static::isMarketingManager() && in_array($group, static::$marketingEditableGroups, true)) {
            return true;
        }

        if (static::isDeveloper() && in_array($group, static::$developerEditableGroups, true)) {
            return true;
        }

        return false;
    }

    public static function canEdit($record): bool
    {
        return static::canCreate();
    }

    public static function canDelete($record): bool
    {
        return static::isAdmin();
    }

    public static function canDeleteAny(): bool
    {
        return static::isAdmin();
    }

    public static function canRestore($record): bool
    {
        return static::isAdmin();
    }

    public static function canRestoreAny(): bool
    {
        return static::isAdmin();
    }

    public static function canForceDelete($record): bool
    {
        return static::isAdmin();
    }

    public static function canForceDeleteAny(): bool
    {
        return static::isAdmin();
    }
}
