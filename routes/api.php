<?php

use App\Http\Controllers\ApiController;
use App\Http\Controllers\ApiMailController;
use App\Http\Middleware\AuthenticationWithQueryString;
use Illuminate\Support\Facades\Route;

Route::post('auth/login', [ApiController::class, 'login']);
// Route::post('auth/register', [ApiController::class, 'register']);
Route::post('auth/forgot-password', [ApiController::class, 'sendResetLinkEmail']);

Route::group(['middleware' => ['auth:sanctum', 'verified']], function () {
    Route::get('/user', [ApiController::class, 'getUser'])->name('user.info');
    Route::get('/domain', [ApiController::class, 'getDomains'])->name('domain.index');
    Route::get('/email', [ApiController::class, 'getEmails'])->name('email.index');
    Route::post('/email/create', [ApiController::class, 'createEmail'])->name('email.create');
    Route::get('/email/{id}', [ApiController::class, 'getEmailMessages'])->name('email.show');
    Route::get('/message/{id}', [ApiController::class, 'getMessage'])->middleware(['api-limit', 'api-log'])->name('message.show');
    Route::post('/netflix/get-code', [ApiController::class, 'getNetflixCode'])->name('netflix.get-code');

    // manager fcm token
    Route::post('/user/update-fcm-token', [ApiController::class, 'updateFcmToken']);
});

Route::group(['middleware' => [AuthenticationWithQueryString::class, 'verified']], function () {
    Route::get('message', [ApiMailController::class, 'getMessageOfMail'])->name('mail.inbox');
});
