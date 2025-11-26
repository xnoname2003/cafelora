<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class Pos extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.pos';

    public static function canAccess(): bool
    {
        return auth()->user()?->can('access-pos') ?? false;
    }


}
