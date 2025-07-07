<?php

namespace App\Filament\Resources\EmailOtpRegexRuleResource\Pages;

use App\Filament\Resources\EmailOtpRegexRuleResource;
use Filament\Resources\Pages\CreateRecord;

class CreateEmailOtpRegexRule extends CreateRecord
{
    protected static string $resource = EmailOtpRegexRuleResource::class;

    protected function getHeaderActions(): array
    {
        return [

        ];
    }
}
