<?php

namespace App\Listeners;

use BezhanSalleh\FilamentExceptions\Facades\FilamentExceptions;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\Arr;

class NotificationFailed
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @throws BindingResolutionException
     */
    public function handle(\Illuminate\Notifications\Events\NotificationFailed $event): void
    {
        // handler code
        $report = Arr::get($event->data, 'report');

        $error = $report->error();

        if ($error) {
            FilamentExceptions::report($error);
        }
        /**
         * @var \Kreait\Firebase\Messaging\MessageTarget $target
         */
        $target = $report->target();

        $event->notifiable->removeFcmToken($target->value());
    }
}
