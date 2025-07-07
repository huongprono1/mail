<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum UserPlanStatus: string implements HasColor, HasIcon, HasLabel
{
    case Active = 'active';
    case Expired = 'expired';
    case Cancelled = 'cancelled';
    case Pending = 'pending';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::Active => __('Active'),
            self::Expired => __('Expired'),
            self::Cancelled => __('Cancelled'),
            self::Pending => __('Pending'),
        };
    }

    public function getIcon(): string
    {
        return match ($this) {
            self::Active => 'heroicon-o-check-circle',    // Icon xanh thành công
            self::Expired => 'heroicon-o-x-circle',        // Icon đỏ hết hạn
            self::Cancelled => 'heroicon-o-no-symbol',           // Icon cancel
            self::Pending => 'heroicon-o-clock',           // Icon chờ xử lý
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            self::Active => 'success',
            self::Expired, self::Cancelled => 'danger',
            self::Pending => 'warning',
        };
    }
}
