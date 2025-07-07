<?php

namespace App\Filament\Resources\EmailOtpRegexRuleResource\Pages;

use App\Filament\Resources\EmailOtpRegexRuleResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListEmailOtpRegexRules extends ListRecords
{
    protected static string $resource = EmailOtpRegexRuleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
