<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class Home extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.home';

    protected static bool $shouldRegisterNavigation = false;

    public static function canAccess(): bool
    {
        return true ;
    }
    
}
