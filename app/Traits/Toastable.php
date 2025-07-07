<?php

declare(strict_types=1);

namespace App\Traits;

use Filament\Notifications\Notification;

trait Toastable
{
    protected function error(string $message, array $replace = []): bool
    {
        Notification::make()
            ->title($message)
            ->danger()
            ->color('danger')
            ->send();

        return false;
    }

    protected function info(string $message, array $replace = []): bool
    {
        Notification::make()
            ->title($message)
            ->info()
            ->color('info')
            ->send();

        return false;
    }

    protected function success(string $message, array $replace = []): bool
    {
        Notification::make()
            ->title($message)
            ->success()
            ->color('success')
            ->send();

        return false;
    }

    protected function warning(string $message, array $replace = []): bool
    {
        Notification::make()
            ->title($message)
            ->warning()
            ->color('warning')
            ->send();

        return false;
    }
}
