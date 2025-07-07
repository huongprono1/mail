<?php

namespace App\Filament\App\Pages;

use Filament\Pages\Page;
use Illuminate\Contracts\Support\Htmlable;

class Home extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-home';

    protected static string $view = 'filament.app.pages.home';

    protected ?string $heading = '';

    public static function getRoutePath(): string
    {
        return '';
    }

    public static function getNavigationLabel(): string
    {
        return __('Home');
    }

    public function getTitle(): string|Htmlable
    {
        return __('Temporary Edu Mail Solution - Lifetime Free Service - Free API Support');
    }

    public function mount(): void
    {
        //        SEOMeta::setDescription('Home');
    }
}
