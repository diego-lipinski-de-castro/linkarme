<?php

use App\Http\Controllers\Client\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Client\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Client\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Client\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Client\Auth\NewPasswordController;
use App\Http\Controllers\Client\Auth\PasswordResetLinkController;
use App\Http\Controllers\Client\Auth\VerifyEmailController;
use App\Http\Controllers\Client\DashboardController;
use App\Http\Controllers\Client\NoteController;
use App\Http\Controllers\Client\NotificationController;
use App\Http\Controllers\Client\OrderController;
use App\Http\Controllers\Client\ProfileController;
use App\Http\Controllers\Client\SiteController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::group([
    'prefix' => 'clientes',
    'namespace' => 'Client',
    'as' => 'client.',
], function () {

    // 
    Route::get('dashboard', [DashboardController::class, 'index'])
        ->name('dashboard')
        ->middleware(['auth:client']);

    Route::get('notifications', [NotificationController::class, 'index'])
        ->name('notifications')
        ->middleware(['auth:client']);

    Route::get('sites', [SiteController::class, 'index'])
        ->name('sites.index')
        ->middleware(['auth:client']);

    Route::post('sites/{site}/favorite', [SiteController::class, 'favorite'])
        ->name('sites.favorite')
        ->middleware(['auth:client']);

    Route::get('sites/{site}', [SiteController::class, 'show'])
        ->name('sites.show')
        ->middleware(['auth:client']);

    Route::put('sites/{site}/note', [NoteController::class, 'update'])->name('notes.update')->middleware(['auth:client']);

    Route::get('orders', [OrderController::class, 'index'])
        ->name('orders.index')
        ->middleware(['auth:client']);

    // guest
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])
        ->middleware('guest:client')
        ->name('login');

    Route::post('/login', [AuthenticatedSessionController::class, 'store'])
        ->middleware('guest:client');

    Route::get('/esqueci-minha-senha', [PasswordResetLinkController::class, 'create'])
        ->middleware('guest:client')
        ->name('password.request');

    Route::post('/esqueci-minha-senha', [PasswordResetLinkController::class, 'store'])
        ->middleware('guest:client')
        ->name('password.email');

    Route::get('/redefinir-senha/{token}', [NewPasswordController::class, 'create'])
        ->middleware('guest:client')
        ->name('password.reset');

    Route::post('/redefinir-senha', [NewPasswordController::class, 'store'])
        ->middleware('guest:client')
        ->name('password.update');

    // auth
    Route::get('/verificar-email', [EmailVerificationPromptController::class, '__invoke'])
        ->middleware('auth:client')
        ->name('verification.notice');

    Route::get('/verificar-email/{id}/{hash}', [VerifyEmailController::class, '__invoke'])
        ->middleware(['auth:client', 'signed', 'throttle:6,1'])
        ->name('verification.verify');

    Route::post('/email/verificar', [EmailVerificationNotificationController::class, 'store'])
        ->middleware(['auth:client', 'throttle:6,1'])
        ->name('verification.send');

    Route::get('/confirmar-senha', [ConfirmablePasswordController::class, 'show'])
        ->middleware('auth:client')
        ->name('password.confirm');

    Route::post('/confirmar-senha', [ConfirmablePasswordController::class, 'store'])
        ->middleware('auth:client');

    Route::post('/sair', [AuthenticatedSessionController::class, 'destroy'])
        ->middleware('auth:client')
        ->name('logout');

    Route::post('/atualizar-senha', [PasswordResetLinkController::class, 'submit'])
        ->middleware('auth:client')
        ->name('password.submit');

    // profile
    Route::get('/profile', [ProfileController::class, 'show'])
        ->middleware('auth:client')
        ->name('profile.show');

    Route::put('/profile-information', [ProfileController::class, 'update'])
        ->middleware('auth:client')
        ->name('profile-information.update');

    Route::delete('/profile-photo', [ProfileController::class, 'destroyPhoto'])
        ->middleware('auth:client')
        ->name('current-user-photo.destroy');

    Route::put('/password', [ProfileController::class, 'updatePassword'])
        ->middleware('auth:client')
        ->name('password.update');
});