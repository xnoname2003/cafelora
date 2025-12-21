<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasAvatar;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class User extends Authenticatable implements FilamentUser, HasAvatar
{
    use HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    public function canAccessPanel(\Filament\Panel $panel): bool
    {
        return true;
    }

    /**
     * Filament: Avatar URL
     */
    public function getFilamentAvatarUrl(): ?string
    {
        return null;
    }
}
