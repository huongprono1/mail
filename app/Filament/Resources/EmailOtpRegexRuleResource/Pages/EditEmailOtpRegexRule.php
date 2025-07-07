<?php

namespace App\Filament\Resources\EmailOtpRegexRuleResource\Pages;

use App\Filament\Resources\EmailOtpRegexRuleResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditEmailOtpRegexRule extends EditRecord
{
    protected static string $resource = EmailOtpRegexRuleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
