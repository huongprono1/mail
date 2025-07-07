<?php

namespace App\Filament\App\Pages;

use Filament\Pages\Page;
use Illuminate\Contracts\Support\Htmlable;

class Blog extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.app.pages.blog.index';

    protected static bool $shouldRegisterNavigation = false;

    protected static ?int $navigationSort = 3;

    public static function getNavigationLabel(): string
    {
        return __('Blog');
    }

    public function getTitle(): string|Htmlable
    {
        return __('Blog sharing tips and tricks');
    }
}
