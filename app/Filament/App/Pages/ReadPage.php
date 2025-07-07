<?php

namespace App\Filament\App\Pages;

use Filament\Pages\Page;
use Illuminate\Contracts\Support\Htmlable;

class ReadPage extends Page
{
    public $page;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.app.pages.read-page';

    protected static bool $shouldRegisterNavigation = false;

    protected ?string $heading = '';

    protected ?string $pageTitle = 'Read Page';

    protected static ?string $slug = '/p/{slug}';

    public function getTitle(): string|Htmlable
    {
        return $this->pageTitle;
    }

    public function mount($slug = null): void
    {
        $this->page = \App\Models\Page::query()->where('slug', $slug)?->first() ?? abort(404);
        $this->pageTitle = $this->page->title ?? '';
    }
}
