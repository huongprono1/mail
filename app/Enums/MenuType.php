<?php

declare(strict_types=1);

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum MenuType: string implements HasColor, HasIcon, HasLabel
{
    case URL = 'url';
    case ROUTE = 'route';

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::URL => 'primary',
            self::ROUTE => 'info',
        };
    }

    public function getIcon(): ?string
    {
        return match ($this) {
            self::URL => 'heroicon-m-pencil',
            self::ROUTE => 'heroicon-m-eye',
        };
    }

    public function getLabel(): ?string
    {
        return match ($this) {
            self::URL => 'URL',
            self::ROUTE => 'Route',
        };
    }
}
