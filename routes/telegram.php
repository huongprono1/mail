<?php

use App\Http\Controllers\TelegramController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'telegram', 'as' => 'telegram.'], function () {
    Route::post('webhook', function () {
        Telegram::commandsHandler(true);

        return response()->json([
            'status' => 'ok',
        ]);
    })->name('webhook');

    Route::post('me', [TelegramController::class, 'me'])->name('me');
});
