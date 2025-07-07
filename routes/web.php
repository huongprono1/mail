<?php

use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Support\Facades\Route;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

Route::group([
    'prefix' => LaravelLocalization::setLocale(),
    'middleware' => ['localeSessionRedirect', 'localizationRedirect', 'localeViewPath', 'laravelLocalizationRedirectFilter'],
], function () {
    Route::post('/email/verification-notification', function (Request $request) {
        $request->user()->sendEmailVerificationNotification();

        return back()->with('message', 'Verification link sent!');
    })->middleware(['auth', 'throttle:3,1'])->name('verification.send');

    Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
        $request->fulfill();

        return redirect('login');
    })->middleware(['auth', 'signed'])->name('verification.verify');

    //    Route::get('/', \App\Livewire\TempMail\Home::class)->name('home');
    //    Route::get('/inbox', \App\Livewire\TempMail\Inbox::class)->name('inbox');
    //    Route::get('/{page:slug}', [\App\Http\Controllers\PageController::class, 'index'])->name('page.show');

    //    Route::get('/{page:slug}', App\Filament\App\Pages\ReadPage::class)->name('page.show');
    //    Route::get('/message/{slug}', App\Filament\App\Pages\ReadMail::class)->name('mail.read');

    require base_path('vendor/filament/filament/routes/web.php');

    Livewire::setUpdateRoute(function ($handle) {
        return Route::post('/livewire/update', $handle);
    });
});

Route::get('ads.txt', function () {
    return setting('ads.ads_txt');
});
/**
 * SOCIAL LOGIN
 */
// Route::controller(\App\Http\Controllers\SocialiteController::class)
//    ->middleware('guest')
//    ->group(function () {
//        Route::get('auth/facebook', 'redirectToFacebook')->name('auth.facebook');
//        Route::get('auth/facebook/callback', 'handleFacebookCallback');
//
//        Route::get('auth/google', 'redirectToGoogle')->name('auth.google');
//        Route::get('auth/google/callback', 'handleGoogleCallback');
//
//        Route::post('auth/google-one-tap', 'handleGoogleOneTapCallback');
//    });

Route::group(['prefix' => 'mail-api', 'as' => 'mail-api.'], function () {
    Route::post('store-mail', [\App\Http\Controllers\MailController::class, 'storeMail'])->name('store-mail');
    Route::post('check-exist', [\App\Http\Controllers\MailController::class, 'checkMailExists'])->name('check-exist');
});

Route::group(['prefix' => 'webhook', 'as' => 'webhook.', 'middleware' => [\App\Http\Middleware\ForceJsonResponse::class]], function () {
    Route::post('sepay', [\App\Http\Controllers\SePayController::class, 'webhook'])->name('sepay');
    Route::post('paypal', [\App\Http\Controllers\PaypalController::class, 'webhook'])->name('paypal');
});

Route::group(['prefix' => 'callback', 'as' => 'callback.'], function () {
    Route::get('paypal/success', [\App\Http\Controllers\PaypalController::class, 'success'])->name('paypal.success');
    Route::get('paypal/cancel', [\App\Http\Controllers\PaypalController::class, 'cancel'])->name('paypal.cancel');
});

Route::passkeys();

// Route::mailPreview('email-preview');
