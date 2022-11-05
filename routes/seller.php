<?php

use App\Http\Controllers\Seller\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Seller\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Seller\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Seller\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Seller\Auth\NewPasswordController;
use App\Http\Controllers\Seller\Auth\PasswordResetLinkController;
use App\Http\Controllers\Seller\OrderController;
use App\Http\Controllers\Seller\SiteController;
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => 'vendedores',
    'as' => 'seller.',
], function () {
    Route::get('sites', [SiteController::class, 'index'])
        ->name('sites.index')
        ->middleware(['auth:seller']);

    Route::get('sites/adicionar', [SiteController::class, 'create'])
        ->name('sites.create')
        ->middleware(['auth:seller']);

    Route::post('sites', [SiteController::class, 'store'])
        ->name('sites.store')
        ->middleware(['auth:seller']);

    Route::post('sites/offer', [SiteController::class, 'offer'])
        ->name('sites.offer')
        ->middleware(['auth:seller']);

    Route::get('sites/{site}/edit', [SiteController::class, 'edit'])
        ->name('sites.edit')
        ->middleware(['auth:seller']);

    Route::put('sites/{site}', [SiteController::class, 'update'])
        ->name('sites.update')
        ->middleware(['auth:seller']);

    Route::delete('sites/{site}', [SiteController::class, 'destroy'])->withTrashed()->name('sites.destroy')->middleware(['auth:seller']);
    Route::post('sites/{site}/toggle', [SiteController::class, 'toggle'])->withTrashed()->name('sites.toggle')->middleware(['auth:seller']);

    Route::get('orders', [OrderController::class, 'index'])
        ->name('orders.index')
        ->middleware(['auth:seller']);

    // 
    Route::middleware('guest:seller')->group(function () {
        Route::get('login', [AuthenticatedSessionController::class, 'create'])
                    ->name('login');

        Route::post('login', [AuthenticatedSessionController::class, 'store']);

        Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])
                    ->name('password.request');

        Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])
                    ->name('password.email');

        Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])
                    ->name('password.reset');

        Route::post('reset-password', [NewPasswordController::class, 'store'])
                    ->name('password.update');
    });

    Route::middleware('auth:seller')->group(function () {
        Route::get('verify-email', [EmailVerificationPromptController::class, '__invoke'])
                    ->name('verification.notice');

        Route::get('verify-email/{id}/{hash}', [VerifyEmailController::class, '__invoke'])
                    ->middleware(['signed', 'throttle:6,1'])
                    ->name('verification.verify');

        Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
                    ->middleware('throttle:6,1')
                    ->name('verification.send');

        Route::get('confirm-password', [ConfirmablePasswordController::class, 'show'])
                    ->name('password.confirm');

        Route::post('confirm-password', [ConfirmablePasswordController::class, 'store']);

        Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
                    ->name('logout');
    });
});
