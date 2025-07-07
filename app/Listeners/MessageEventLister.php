<?php

namespace App\Listeners;

use App\Events\NewMessageEvent;
use App\Models\Message;
use App\Notifications\NewEmailMessage;
use App\Services\OtpService;
use App\Traits\HasNetflixToolkit;
use BezhanSalleh\FilamentExceptions\Facades\FilamentExceptions;
use DiDom\Exceptions\InvalidSelectorException;
use Illuminate\Contracts\Container\BindingResolutionException;
use Mockery\Exception;

class MessageEventLister
{
    use HasNetflixToolkit;

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
    public function handle(NewMessageEvent $event): void
    {
        $message = $event->message;
        $mail = $event->mail;

        // process netflix
        try {
            $this->processNetflix($message);
        } catch (Exception|InvalidSelectorException $e) {
            FilamentExceptions::report($e);
        }

        // send notification
        defer(function () use ($mail, $message) {
            $mail->user?->notify(new NewEmailMessage($message));
        });

        // auto regex otp
        $this->autoRegexOtp($message);
    }

    private function autoRegexOtp(Message $message): void
    {
        $otpService = new OtpService($message);
        $otp_code = $otpService->getOtpCode();

        if ($otp_code) {
            $message->otp_code = $otp_code;
            $message->save();
        }
    }
}
