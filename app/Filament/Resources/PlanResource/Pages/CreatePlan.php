<?php

namespace App\Filament\Resources\PlanResource\Pages;

use App\Filament\Resources\PlanResource;
use Filament\Actions\LocaleSwitcher;
use Filament\Resources\Pages\CreateRecord;

class CreatePlan extends CreateRecord
{
    use CreateRecord\Concerns\Translatable;

    protected static string $resource = PlanResource::class;

    protected function getHeaderActions(): array
    {
        return [

            LocaleSwitcher::make(),
        ];
    }
}
