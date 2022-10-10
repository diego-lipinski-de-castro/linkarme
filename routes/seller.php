<?php

use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => 'vendedores',
    'as' => 'seller.',
], function () {
    Route::get('sites', [\App\Http\Controllers\Seller\SiteController::class, 'index'])
        ->name('sites.index')
        ->middleware(['auth:seller']);

    Route::get('sites/adicionar', [\App\Http\Controllers\Seller\SiteController::class, 'create'])
        ->name('sites.create')
        ->middleware(['auth:seller']);

    Route::post('sites', [\App\Http\Controllers\Seller\SiteController::class, 'store'])
        ->name('sites.store')
        ->middleware(['auth:seller']);

    Route::post('sites/offer', [\App\Http\Controllers\Seller\SiteController::class, 'offer'])
        ->name('sites.offer')
        ->middleware(['auth:seller']);

    Route::get('sites/{site}/edit', [\App\Http\Controllers\Seller\SiteController::class, 'edit'])
        ->name('sites.edit')
        ->middleware(['auth:seller']);

    Route::put('sites/{site}', [\App\Http\Controllers\Seller\SiteController::class, 'update'])
        ->name('sites.update')
        ->middleware(['auth:seller']);

    Route::delete('sites/{site}', [\App\Http\Controllers\Seller\SiteController::class, 'destroy'])->withTrashed()->name('sites.destroy')->middleware(['auth:seller']);
    Route::post('sites/{site}/toggle', [\App\Http\Controllers\Seller\SiteController::class, 'toggle'])->withTrashed()->name('sites.toggle')->middleware(['auth:seller']);

    Route::get('orders', [\App\Http\Controllers\Seller\OrderController::class, 'index'])
        ->name('orders.index')
        ->middleware(['auth:seller']);

    // 
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
