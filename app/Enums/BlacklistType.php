<?php

declare(strict_types=1);

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum BlacklistType: string implements HasColor, HasIcon, HasLabel
{
    case DOMAIN = 'domain';
    case KEYWORD = 'keyword';

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::DOMAIN => 'primary',
            self::KEYWORD => 'info',
        };
    }

    public function getIcon(): ?string
    {
        return match ($this) {
            self::DOMAIN => 'heroicon-o-globe-alt',
            self::KEYWORD => 'heroicon-o-shield-check',
        };
    }

    public function getLabel(): ?string
    {
        return match ($this) {
            self::DOMAIN => 'DOMAIN',
            self::KEYWORD => 'Keyword',
        };
    }
}
