<?php

namespace App\Http\Controllers;

use Telegram\Bot\Api;

class TelegramController extends Controller
{
    protected $telegram;

    /**
     * Create a new controller instance.
     */
    public function __construct(Api $telegram)
    {
        $this->telegram = $telegram;
    }

    /**
     * Show the bot information.
     */
    public function me()
    {
        $response = $this->telegram->getMe();

        return $response;
    }
}
