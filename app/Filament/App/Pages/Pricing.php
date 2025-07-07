<?php

namespace App\Filament\App\Pages;

use App\Models\Plan;
use Filament\Pages\Page;

class Pricing extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.app.pages.pricing';

    protected static bool $shouldRegisterNavigation = false;

    private $plans;

    public function mount()
    {
        $this->plans = Plan::active()->orderBy('price')->get();
    }
}
