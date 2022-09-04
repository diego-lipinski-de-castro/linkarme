<?php

use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('login', [\App\Http\Controllers\Auth\AuthenticatedSessionController::class, 'create'])
                ->name('login');

    Route::post('login', [\App\Http\Controllers\Auth\AuthenticatedSessionController::class, 'store']);

    Route::get('forgot-password', [\App\Http\Controllers\Auth\PasswordResetLinkController::class, 'create'])
                ->name('password.request');

    Route::post('forgot-password', [\App\Http\Controllers\Auth\PasswordResetLinkController::class, 'store'])
                ->name('password.email');

    Route::get('reset-password/{token}', [\App\Http\Controllers\Auth\NewPasswordController::class, 'create'])
                ->name('password.reset');

    Route::post('reset-password', [\App\Http\Controllers\Auth\NewPasswordController::class, 'store'])
                ->name('password.update');
});

Route::middleware('auth')->group(function () {
    Route::get('verify-email', [\App\Http\Controllers\Auth\EmailVerificationPromptController::class, '__invoke'])
                ->name('verification.notice');

    Route::get('verify-email/{id}/{hash}', [\App\Http\Controllers\Auth\VerifyEmailController::class, '__invoke'])
                ->middleware(['signed', 'throttle:6,1'])
                ->name('verification.verify');

    Route::post('email/verification-notification', [\App\Http\Controllers\Auth\EmailVerificationNotificationController::class, 'store'])
                ->middleware('throttle:6,1')
                ->name('verification.send');

    Route::get('confirm-password', [\App\Http\Controllers\Auth\ConfirmablePasswordController::class, 'show'])
                ->name('password.confirm');

    Route::post('confirm-password', [\App\Http\Controllers\Auth\ConfirmablePasswordController::class, 'store']);

    Route::post('logout', [\App\Http\Controllers\Auth\AuthenticatedSessionController::class, 'destroy'])
                ->name('logout');
});

//

Route::group([
    'prefix' => 'clientes',
    'as' => 'client.',
], function () {
    Route::middleware('guest:client')->group(function () {
        Route::get('login', [\App\Http\Controllers\Client\Auth\AuthenticatedSessionController::class, 'create'])
                    ->name('login');

        Route::post('login', [\App\Http\Controllers\Client\Auth\AuthenticatedSessionController::class, 'store']);

        Route::get('forgot-password', [\App\Http\Controllers\Client\Auth\PasswordResetLinkController::class, 'create'])
                    ->name('password.request');

        Route::post('forgot-password', [\App\Http\Controllers\Client\Auth\PasswordResetLinkController::class, 'store'])
                    ->name('password.email');

        Route::get('reset-password/{token}', [\App\Http\Controllers\Client\Auth\NewPasswordController::class, 'create'])
                    ->name('password.reset');

        Route::post('reset-password', [\App\Http\Controllers\Client\Auth\NewPasswordController::class, 'store'])
                    ->name('password.update');
    });

    Route::middleware('auth:client')->group(function () {
        Route::get('verify-email', [\App\Http\Controllers\Client\Auth\EmailVerificationPromptController::class, '__invoke'])
                    ->name('verification.notice');

        Route::get('verify-email/{id}/{hash}', [\App\Http\Controllers\Client\Auth\VerifyEmailController::class, '__invoke'])
                    ->middleware(['signed', 'throttle:6,1'])
                    ->name('verification.verify');

        Route::post('email/verification-notification', [\App\Http\Controllers\Client\Auth\EmailVerificationNotificationController::class, 'store'])
                    ->middleware('throttle:6,1')
                    ->name('verification.send');

        Route::get('confirm-password', [\App\Http\Controllers\Client\Auth\ConfirmablePasswordController::class, 'show'])
                    ->name('password.confirm');

        Route::post('confirm-password', [\App\Http\Controllers\Client\Auth\ConfirmablePasswordController::class, 'store']);

        Route::post('logout', [\App\Http\Controllers\Client\Auth\AuthenticatedSessionController::class, 'destroy'])
                    ->name('logout');
    });
});

//

Route::group([
    'prefix' => 'vendedores',
    'as' => 'seller.',
], function () {
    Route::middleware('guest:seller')->group(function () {
        Route::get('login', [\App\Http\Controllers\Seller\Auth\AuthenticatedSessionController::class, 'create'])
                    ->name('login');

        Route::post('login', [\App\Http\Controllers\Seller\Auth\AuthenticatedSessionController::class, 'store']);

        Route::get('forgot-password', [\App\Http\Controllers\Seller\Auth\PasswordResetLinkController::class, 'create'])
                    ->name('password.request');

        Route::post('forgot-password', [\App\Http\Controllers\Seller\Auth\PasswordResetLinkController::class, 'store'])
                    ->name('password.email');

        Route::get('reset-password/{token}', [\App\Http\Controllers\Seller\Auth\NewPasswordController::class, 'create'])
                    ->name('password.reset');

        Route::post('reset-password', [\App\Http\Controllers\Seller\Auth\NewPasswordController::class, 'store'])
                    ->name('password.update');
    });

    Route::middleware('auth:seller')->group(function () {
        Route::get('verify-email', [\App\Http\Controllers\Seller\Auth\EmailVerificationPromptController::class, '__invoke'])
                    ->name('verification.notice');

        Route::get('verify-email/{id}/{hash}', [\App\Http\Controllers\Seller\Auth\VerifyEmailController::class, '__invoke'])
                    ->middleware(['signed', 'throttle:6,1'])
                    ->name('verification.verify');

        Route::post('email/verification-notification', [\App\Http\Controllers\Seller\Auth\EmailVerificationNotificationController::class, 'store'])
                    ->middleware('throttle:6,1')
                    ->name('verification.send');

        Route::get('confirm-password', [\App\Http\Controllers\Seller\Auth\ConfirmablePasswordController::class, 'show'])
                    ->name('password.confirm');

        Route::post('confirm-password', [\App\Http\Controllers\Seller\Auth\ConfirmablePasswordController::class, 'store']);

        Route::post('logout', [\App\Http\Controllers\Seller\Auth\AuthenticatedSessionController::class, 'destroy'])
                    ->name('logout');
    });
});
